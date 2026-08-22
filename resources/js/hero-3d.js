// BuildCares hero 3D — AutoCAD-style architectural scene
//   • Wireframe house drawn line-by-line in stages
//   • Dimension lines with arrowheads + measurement labels
//   • Snap-point markers at corners
//   • XYZ axis gizmo in the corner
//   • AutoCAD crosshair cursor following the mouse
//   • Ambient field of drifting cross markers + dashed construction lines

import * as THREE from 'three';

const PRIMARY = 0x2563eb; // walls
const ACCENT  = 0x0ea5e9; // openings (windows, doors)
const DIM     = 0xf59e0b; // dimension lines (CAD yellow/amber)
const HIDDEN  = 0x94a3b8; // hidden / construction lines
const SOFT    = 0xbfdbfe; // soft accents

// Service-type layer colors (matching how CAD drawings show new vs existing)
const LOFT_C  = 0x10b981; // loft conversion  — emerald
const SIDE_C  = 0xa855f7; // side extension   — purple
const REAR_C  = 0xf97316; // rear extension   — orange

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// ── Generic line helpers ───────────────────────────────────────────────────
function solidEdges(geometry, color, opacity = 1) {
    const edges = new THREE.EdgesGeometry(geometry, 1);
    const mat = new THREE.LineBasicMaterial({
        color,
        transparent: true,
        opacity,
    });
    const seg = new THREE.LineSegments(edges, mat);
    seg.userData.targetOpacity = opacity;
    return seg;
}

function solidMesh(geometry, color, opacity = 0.85) {
    const mat = new THREE.MeshPhongMaterial({
        color,
        transparent: true,
        opacity,
        side: THREE.DoubleSide,
        shininess: 35,
    });
    const mesh = new THREE.Mesh(geometry, mat);
    mesh.userData.targetOpacity = opacity;
    return mesh;
}

function dashedEdges(geometry, color, opacity = 1, dashSize = 0.08, gapSize = 0.06) {
    const edges = new THREE.EdgesGeometry(geometry, 1);
    const mat = new THREE.LineDashedMaterial({
        color, transparent: true, opacity, dashSize, gapSize,
    });
    const seg = new THREE.LineSegments(edges, mat);
    seg.computeLineDistances();
    return seg;
}

function lineFromPoints(points, color, opacity = 1, dashed = false) {
    const geo = new THREE.BufferGeometry().setFromPoints(points);
    const mat = dashed
        ? new THREE.LineDashedMaterial({ color, transparent: true, opacity, dashSize: 0.08, gapSize: 0.06 })
        : new THREE.LineBasicMaterial({ color, transparent: true, opacity });
    const line = new THREE.Line(geo, mat);
    if (dashed) line.computeLineDistances();
    return line;
}

// ── Snap point marker: small square outline at a vertex ────────────────────
function snapMarker(pos, color = ACCENT, size = 0.12) {
    const s = size / 2;
    const pts = [
        new THREE.Vector3(-s, -s, 0), new THREE.Vector3( s, -s, 0),
        new THREE.Vector3( s, -s, 0), new THREE.Vector3( s,  s, 0),
        new THREE.Vector3( s,  s, 0), new THREE.Vector3(-s,  s, 0),
        new THREE.Vector3(-s,  s, 0), new THREE.Vector3(-s, -s, 0),
    ];
    const geo = new THREE.BufferGeometry().setFromPoints(pts);
    const mat = new THREE.LineBasicMaterial({ color, transparent: true, opacity: 0 });
    const marker = new THREE.LineSegments(geo, mat);
    marker.position.copy(pos);
    return marker;
}

// ── Dimension: leader extension lines + main line with arrowheads ──────────
//   from/to: endpoints of the measured edge (3D)
//   normal:  perpendicular offset direction (unit vector)
//   offset:  distance the dimension line sits away from the edge
//   color, name returns object with the group and the worldspace label-anchor
function dimensionLine(from, to, normal, offset, color = DIM) {
    const group = new THREE.Group();

    const fromOff = from.clone().addScaledVector(normal, offset);
    const toOff   = to.clone().addScaledVector(normal, offset);

    // Extension lines: from the edge end to the dimension line
    group.add(lineFromPoints(
        [from.clone().addScaledVector(normal, offset * 0.1), fromOff.clone().addScaledVector(normal, 0.15)],
        color, 0
    ));
    group.add(lineFromPoints(
        [to.clone().addScaledVector(normal, offset * 0.1), toOff.clone().addScaledVector(normal, 0.15)],
        color, 0
    ));

    // Main dimension line
    group.add(lineFromPoints([fromOff, toOff], color, 0));

    // Arrowheads — two short tick lines at 45° at each end
    const dir = toOff.clone().sub(fromOff).normalize();
    const perp = new THREE.Vector3().crossVectors(dir, new THREE.Vector3(0, 0, 1)).normalize();
    if (perp.lengthSq() < 0.01) {
        perp.crossVectors(dir, new THREE.Vector3(0, 1, 0)).normalize();
    }
    const head = 0.12;
    const arrow = (tip, sign) => {
        const back = tip.clone().addScaledVector(dir, sign * head);
        group.add(lineFromPoints([tip, back.clone().addScaledVector(perp, head * 0.4)], color, 0));
        group.add(lineFromPoints([tip, back.clone().addScaledVector(perp, -head * 0.4)], color, 0));
    };
    arrow(fromOff, 1);
    arrow(toOff, -1);

    // Anchor for an HTML label (midpoint)
    const labelAnchor = fromOff.clone().add(toOff).multiplyScalar(0.5).addScaledVector(normal, 0.15);

    return { group, labelAnchor };
}

// ── XYZ gizmo (small axis indicator) ────────────────────────────────────────
function axisGizmo() {
    const group = new THREE.Group();
    const len = 0.5;
    group.add(lineFromPoints([new THREE.Vector3(0,0,0), new THREE.Vector3(len,0,0)], 0xef4444, 0.95)); // X red
    group.add(lineFromPoints([new THREE.Vector3(0,0,0), new THREE.Vector3(0,len,0)], 0x22c55e, 0.95)); // Y green
    group.add(lineFromPoints([new THREE.Vector3(0,0,0), new THREE.Vector3(0,0,len)], 0x3b82f6, 0.95)); // Z blue
    return group;
}

// ── Build the house structure as ordered "stages" for sequential reveal ────
function buildHouseStages() {
    const stages = [];

    // Stage 0: footprint plate + grid
    const plate = new THREE.Group();
    const footprint = lineFromPoints([
        new THREE.Vector3(-2.5, 0, -2),
        new THREE.Vector3( 2.5, 0, -2),
        new THREE.Vector3( 2.5, 0,  2),
        new THREE.Vector3(-2.5, 0,  2),
        new THREE.Vector3(-2.5, 0, -2),
    ], PRIMARY, 0, true);
    plate.add(footprint);
    const grid = new THREE.GridHelper(10, 20, SOFT, SOFT);
    grid.material.transparent = true;
    grid.material.opacity = 0;
    grid.userData.targetOpacity = 0.22;
    plate.add(grid);
    plate.userData.targetOpacity = 0.85;
    stages.push(plate);

    // Stage 1: walls (main box)
    const walls = new THREE.Group();
    const wallGeo = new THREE.BoxGeometry(4, 2.2, 3);
    const wallMesh = solidMesh(wallGeo, 0xebf2fa, 0.88);
    wallMesh.position.y = 1.1;
    walls.add(wallMesh);
    const wallBox = solidEdges(wallGeo, PRIMARY, 0);
    wallBox.position.y = 1.1;
    walls.add(wallBox);
    walls.userData.targetOpacity = 0.95;
    stages.push(walls);

    // Stage 2: roof
    const roof = new THREE.Group();
    const roofShape = new THREE.Shape();
    roofShape.moveTo(-2.15, 0);
    roofShape.lineTo(2.15, 0);
    roofShape.lineTo(0, 1.4);
    roofShape.closePath();
    const roofGeo = new THREE.ExtrudeGeometry(roofShape, { depth: 3.15, bevelEnabled: false });
    roofGeo.translate(0, 2.2, -1.575);
    const roofMesh = solidMesh(roofGeo, 0x3b82f6, 0.80);
    roof.add(roofMesh);
    const roofEdges = solidEdges(roofGeo, PRIMARY, 0);
    roof.add(roofEdges);

    // Ridge line — emphasized
    roof.add(lineFromPoints([
        new THREE.Vector3(0, 3.6, -1.575),
        new THREE.Vector3(0, 3.6,  1.575),
    ], PRIMARY, 0));

    roof.userData.targetOpacity = 0.95;
    stages.push(roof);

    // Stage 3: openings — door + windows (different color)
    const openings = new THREE.Group();

    const doorGeo = new THREE.BoxGeometry(0.6, 1.2, 0.02);
    const doorMesh = solidMesh(doorGeo, 0x0284c7, 0.9);
    doorMesh.position.set(0, 0.6, 1.501);
    openings.add(doorMesh);
    const door = solidEdges(doorGeo, ACCENT, 0);
    door.position.set(0, 0.6, 1.501);
    openings.add(door);

    // Front windows + mullion crosses
    [-1.2, 1.2].forEach((x) => {
        const winGeo = new THREE.BoxGeometry(0.7, 0.7, 0.02);
        const winMesh = solidMesh(winGeo, 0x38bdf8, 0.75);
        winMesh.position.set(x, 1.4, 1.501);
        openings.add(winMesh);
        const w = solidEdges(winGeo, ACCENT, 0);
        w.position.set(x, 1.4, 1.501);
        openings.add(w);

        const cross = new THREE.BufferGeometry().setFromPoints([
            new THREE.Vector3(x, 1.05, 1.505), new THREE.Vector3(x, 1.75, 1.505),
            new THREE.Vector3(x - 0.35, 1.4, 1.505), new THREE.Vector3(x + 0.35, 1.4, 1.505),
        ]);
        const lines = new THREE.LineSegments(
            cross,
            new THREE.LineBasicMaterial({ color: SOFT, transparent: true, opacity: 0 })
        );
        openings.add(lines);
    });

    // Side windows
    [[-2.001, 1.4, 0], [2.001, 1.4, 0]].forEach(([x, y, z]) => {
        const sideWinGeo = new THREE.BoxGeometry(0.9, 0.6, 0.02);
        const sideWinMesh = solidMesh(sideWinGeo, 0x38bdf8, 0.75);
        sideWinMesh.position.set(x, y, z);
        sideWinMesh.rotation.y = Math.PI / 2;
        openings.add(sideWinMesh);
        const sideWin = solidEdges(sideWinGeo, ACCENT, 0);
        sideWin.position.set(x, y, z);
        sideWin.rotation.y = Math.PI / 2;
        openings.add(sideWin);
    });

    openings.userData.targetOpacity = 0.9;
    stages.push(openings);

    // Stage 4: hidden (back) edges as dashed lines for CAD authenticity
    const hidden = new THREE.Group();
    const hiddenBox = dashedEdges(new THREE.BoxGeometry(4, 2.2, 3), HIDDEN, 0, 0.08, 0.08);
    hiddenBox.position.y = 1.1;
    hidden.add(hiddenBox);
    hidden.userData.targetOpacity = 0.25;
    stages.push(hidden);

    // Stage 5: snap markers at all corners
    const snaps = new THREE.Group();
    const corners = [
        new THREE.Vector3(-2, 0,  1.5), new THREE.Vector3( 2, 0,  1.5),
        new THREE.Vector3(-2, 0, -1.5), new THREE.Vector3( 2, 0, -1.5),
        new THREE.Vector3(-2, 2.2,  1.5), new THREE.Vector3( 2, 2.2,  1.5),
        new THREE.Vector3(-2, 2.2, -1.5), new THREE.Vector3( 2, 2.2, -1.5),
        new THREE.Vector3(0, 3.6,  1.575), new THREE.Vector3(0, 3.6, -1.575),
    ];
    corners.forEach((c) => snaps.add(snapMarker(c, ACCENT, 0.14)));
    snaps.userData.targetOpacity = 0.9;
    stages.push(snaps);

    // Each extension stage exposes a labelAnchor so the HTML overlay can pin a tag onto it.
    const extensionLabels = [];

    // Stage 6: Loft conversion — rear dormer on the +X roof slope
    const loft = new THREE.Group();
    const dormerGeo = new THREE.BoxGeometry(0.8, 0.7, 0.9);
    const dormerMesh = solidMesh(dormerGeo, LOFT_C, 0.75);
    dormerMesh.position.set(2.05, 2.95, -0.5);
    loft.add(dormerMesh);
    const dormer = solidEdges(dormerGeo, LOFT_C, 0);
    dormer.position.set(2.05, 2.95, -0.5);
    loft.add(dormer);

    // Dormer window face (facing +X)
    const dormerWinGeo = new THREE.BoxGeometry(0.02, 0.45, 0.6);
    const dormerWinMesh = solidMesh(dormerWinGeo, 0x38bdf8, 0.85);
    dormerWinMesh.position.set(2.46, 2.95, -0.5);
    loft.add(dormerWinMesh);
    const dormerWin = solidEdges(dormerWinGeo, LOFT_C, 0);
    dormerWin.position.set(2.46, 2.95, -0.5);
    loft.add(dormerWin);

    // Internal "new loft floor" line
    const loftFloor = dashedEdges(
        new THREE.PlaneGeometry(3.0, 2.6).rotateX(-Math.PI / 2),
        LOFT_C, 0, 0.12, 0.08
    );
    loftFloor.position.y = 2.6;
    loft.add(loftFloor);
    loft.userData.targetOpacity = 0.95;
    stages.push(loft);

    // Stage 7: Double-storey side extension (extends +X)
    const sideExt = new THREE.Group();
    const sideBoxGeo = new THREE.BoxGeometry(2.5, 2.2, 3);
    const sideBoxMesh = solidMesh(sideBoxGeo, SIDE_C, 0.75);
    sideBoxMesh.position.set(3.25, 1.1, 0);
    sideExt.add(sideBoxMesh);
    const sideBox = solidEdges(sideBoxGeo, SIDE_C, 0);
    sideBox.position.set(3.25, 1.1, 0);
    sideExt.add(sideBox);

    // First-floor slab line
    sideExt.add(lineFromPoints([
        new THREE.Vector3(2.0, 1.2, -1.5), new THREE.Vector3(4.5, 1.2, -1.5),
        new THREE.Vector3(4.5, 1.2, -1.5), new THREE.Vector3(4.5, 1.2,  1.5),
        new THREE.Vector3(4.5, 1.2,  1.5), new THREE.Vector3(2.0, 1.2,  1.5),
    ], SIDE_C, 0));
    // Two windows per floor on the outer (+X) face
    [-0.7, 0.7].forEach((z) => {
        [0.5, 1.65].forEach((y) => {
            const sideWindowGeo = new THREE.BoxGeometry(0.02, 0.55, 0.55);
            const sideWindowMesh = solidMesh(sideWindowGeo, 0x38bdf8, 0.85);
            sideWindowMesh.position.set(4.51, y + 0.15, z);
            sideExt.add(sideWindowMesh);
            const w = solidEdges(sideWindowGeo, SIDE_C, 0);
            w.position.set(4.51, y + 0.15, z);
            sideExt.add(w);
        });
    });
    sideExt.userData.targetOpacity = 0.95;
    stages.push(sideExt);

    // Stage 8: Single-storey rear extension (extends -Z)
    const rearExt = new THREE.Group();
    const rearBoxGeo = new THREE.BoxGeometry(4, 1.0, 2);
    const rearBoxMesh = solidMesh(rearBoxGeo, REAR_C, 0.75);
    rearBoxMesh.position.set(0, 0.5, -2.5);
    rearExt.add(rearBoxMesh);
    const rearBox = solidEdges(rearBoxGeo, REAR_C, 0);
    rearBox.position.set(0, 0.5, -2.5);
    rearExt.add(rearBox);

    // Large glazed rear opening (e.g. bi-fold doors) on -Z face
    const rearGlassGeo = new THREE.BoxGeometry(3.0, 0.7, 0.02);
    const rearGlassMesh = solidMesh(rearGlassGeo, 0x38bdf8, 0.85);
    rearGlassMesh.position.set(0, 0.55, -3.51);
    rearExt.add(rearGlassMesh);
    const rearGlass = solidEdges(rearGlassGeo, REAR_C, 0);
    rearGlass.position.set(0, 0.55, -3.51);
    rearExt.add(rearGlass);

    // Mullion divisions
    [-1.0, 0, 1.0].forEach((x) => {
        rearExt.add(lineFromPoints([
            new THREE.Vector3(x, 0.2, -3.515),
            new THREE.Vector3(x, 0.9, -3.515),
        ], REAR_C, 0));
    });
    rearExt.userData.targetOpacity = 0.95;
    stages.push(rearExt);

    return { stages, extensionLabels };
}

// ── Build dimension lines (will be revealed after structure) ───────────────
function buildDimensions() {
    const dims = new THREE.Group();
    const labels = []; // {anchor: Vector3, text: string}

    // Width (front, X-axis)
    {
        const d = dimensionLine(
            new THREE.Vector3(-2, 0, 1.5),
            new THREE.Vector3( 2, 0, 1.5),
            new THREE.Vector3(0, 0, 1),
            1.0,
        );
        dims.add(d.group);
        labels.push({ anchor: d.labelAnchor, text: '6.00m' });
    }

    // Height (right side, Y-axis)
    {
        const d = dimensionLine(
            new THREE.Vector3(2, 0, 1.5),
            new THREE.Vector3(2, 2.2, 1.5),
            new THREE.Vector3(1, 0, 0),
            1.0,
        );
        dims.add(d.group);
        labels.push({ anchor: d.labelAnchor, text: '3.30m' });
    }

    // Depth (right side, Z-axis)
    {
        const d = dimensionLine(
            new THREE.Vector3(2, 0,  1.5),
            new THREE.Vector3(2, 0, -1.5),
            new THREE.Vector3(1, 0, 0),
            2.2,
        );
        dims.add(d.group);
        labels.push({ anchor: d.labelAnchor, text: '4.50m' });
    }

    // Roof ridge height (left side total)
    {
        const d = dimensionLine(
            new THREE.Vector3(-2, 0, 1.5),
            new THREE.Vector3(-2, 3.6, 1.5),
            new THREE.Vector3(-1, 0, 0),
            1.0,
        );
        dims.add(d.group);
        labels.push({ anchor: d.labelAnchor, text: '5.40m' });
    }

    return { group: dims, labels };
}

// ── Scene 1: AutoCAD-style house viewer ────────────────────────────────────
export function initHeroHouse(container) {
    if (!container) return;

    const scene = new THREE.Scene();
    // FOV widened and camera pulled back to accommodate the three extensions
    const camera = new THREE.PerspectiveCamera(42, 1, 0.1, 100);
    camera.position.set(9.5, 6, 11.5);
    const cameraTarget = new THREE.Vector3(0.5, 1.4, -0.5);
    camera.lookAt(cameraTarget);

    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);
    renderer.domElement.style.cssText = 'width:100%;height:100%;display:block;';

    // Ambient and directional lighting for 3D rendered surfaces
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.85);
    scene.add(ambientLight);
    const dirLight1 = new THREE.DirectionalLight(0xffffff, 0.75);
    dirLight1.position.set(12, 18, 14);
    scene.add(dirLight1);
    const dirLight2 = new THREE.DirectionalLight(0xbfdbfe, 0.35);
    dirLight2.position.set(-10, -5, -10);
    scene.add(dirLight2);

    // Build stages (base house + dimensions + 3 extension types)
    const houseGroup = new THREE.Group();
    scene.add(houseGroup);
    const { stages, extensionLabels } = buildHouseStages();
    stages.forEach((s) => houseGroup.add(s));

    // Dimensions (added but invisible until reveal)
    const { group: dimGroup, labels } = buildDimensions();
    dimGroup.userData.targetOpacity = 1;
    houseGroup.add(dimGroup);

    // HTML overlay layer for dimension labels (created on demand)
    let labelLayer = container.querySelector('.cad-labels');
    if (!labelLayer) {
        labelLayer = document.createElement('div');
        labelLayer.className = 'cad-labels';
        labelLayer.style.cssText = 'position:absolute;inset:0;pointer-events:none;font-family:DM Sans,system-ui,sans-serif;';
        container.appendChild(labelLayer);
    }
    const labelEls = labels.map(({ text }) => {
        const el = document.createElement('div');
        el.textContent = text;
        el.style.cssText = `
            position:absolute; transform:translate(-50%,-50%);
            padding:1px 6px; font-size:9px; font-weight:700; letter-spacing:0.08em;
            color:#f59e0b; background:rgba(255,255,255,0.92); border:1px solid rgba(245,158,11,0.4);
            border-radius:1px; white-space:nowrap; opacity:0; transition:opacity 0.4s ease;
        `;
        labelLayer.appendChild(el);
        return el;
    });

    // Extension-type labels (LOFT / SIDE / REAR) — bolder chips in their layer color
    const extLabelEls = extensionLabels.map(({ text, color }) => {
        const el = document.createElement('div');
        el.textContent = text;
        const rgb = hexToRgb(color);
        el.style.cssText = `
            position:absolute; transform:translate(-50%,-50%);
            padding:3px 8px; font-size:9px; font-weight:800; letter-spacing:0.1em;
            color:#ffffff; background:${color}; border:1px solid rgba(${rgb},0.85);
            border-radius:1px; white-space:nowrap; opacity:0; transition:opacity 0.4s ease;
            box-shadow:0 2px 8px rgba(${rgb},0.35);
        `;
        labelLayer.appendChild(el);
        return el;
    });

    function hexToRgb(hex) {
        const h = hex.replace('#', '');
        const n = parseInt(h, 16);
        return `${(n >> 16) & 255}, ${(n >> 8) & 255}, ${n & 255}`;
    }

    // Axis gizmo (lower-left corner of the canvas — separate ortho scene)
    const gizmoScene = new THREE.Scene();
    const gizmoCam = new THREE.OrthographicCamera(-1, 1, 1, -1, 0.1, 10);
    gizmoCam.position.set(0, 0, 3);
    gizmoCam.lookAt(0, 0, 0);
    const gizmo = axisGizmo();
    gizmoScene.add(gizmo);

    // HTML labels for axis (X/Y/Z)
    const axisLabels = ['X', 'Y', 'Z'].map((letter, i) => {
        const el = document.createElement('div');
        el.textContent = letter;
        const colors = ['#ef4444', '#22c55e', '#3b82f6'];
        el.style.cssText = `
            position:absolute; transform:translate(-50%,-50%);
            font-size:10px; font-weight:800; font-family:DM Sans,system-ui,sans-serif;
            color:${colors[i]}; opacity:0; transition:opacity 0.4s ease;
        `;
        labelLayer.appendChild(el);
        return el;
    });

    // Crosshair cursor overlay (HTML) — large + that follows mouse on this canvas
    const crosshair = document.createElement('div');
    crosshair.className = 'cad-crosshair';
    crosshair.style.cssText = `
        position:absolute; inset:0; pointer-events:none; opacity:0; transition:opacity 0.3s ease;
    `;
    crosshair.innerHTML = `
        <div class="ch-h" style="position:absolute;left:0;right:0;height:1px;background:rgba(37,99,235,0.35);"></div>
        <div class="ch-v" style="position:absolute;top:0;bottom:0;width:1px;background:rgba(37,99,235,0.35);"></div>
        <div class="ch-c" style="position:absolute;width:18px;height:18px;transform:translate(-50%,-50%);">
            <div style="position:absolute;inset:0;border:1px solid rgba(37,99,235,0.55);background:rgba(255,255,255,0.4);"></div>
        </div>
        <div class="ch-coord" style="position:absolute;font-family:DM Sans,system-ui,sans-serif;font-size:9px;font-weight:600;color:#2563eb;background:rgba(255,255,255,0.9);padding:2px 6px;border:1px solid rgba(37,99,235,0.3);letter-spacing:0.05em;white-space:nowrap;"></div>
    `;
    container.appendChild(crosshair);
    const chH = crosshair.querySelector('.ch-h');
    const chV = crosshair.querySelector('.ch-v');
    const chC = crosshair.querySelector('.ch-c');
    const chCoord = crosshair.querySelector('.ch-coord');

    const mouse = { x: 0, y: 0, tx: 0, ty: 0, inside: false };
    let chX = 0, chY = 0;
    const onMove = (e) => {
        const rect = container.getBoundingClientRect();
        const lx = e.clientX - rect.left;
        const ly = e.clientY - rect.top;
        if (lx >= 0 && lx <= rect.width && ly >= 0 && ly <= rect.height) {
            mouse.inside = true;
            chX = lx;
            chY = ly;
            mouse.tx = (lx / rect.width - 0.5) * 2;
            mouse.ty = (ly / rect.height - 0.5) * 2;
        } else {
            mouse.inside = false;
        }
        crosshair.style.opacity = mouse.inside ? '1' : '0';
    };
    window.addEventListener('mousemove', onMove, { passive: true });
    window.addEventListener('mouseleave', () => { mouse.inside = false; crosshair.style.opacity = '0'; }, { passive: true });

    // Resize
    const resize = () => {
        const w = container.clientWidth;
        const h = container.clientHeight;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
    };
    resize();
    const ro = new ResizeObserver(resize);
    ro.observe(container);

    // Visibility gate
    let visible = true;
    const io = new IntersectionObserver(
        ([entry]) => { visible = entry.isIntersecting; },
        { threshold: 0.01 }
    );
    io.observe(container);

    // Stage reveal timings (seconds from start)
    //   0.0 → plate     0.4 → walls    0.9 → roof
    //   1.4 → openings  1.9 → hidden   2.2 → snap markers
    //   2.5 → dimensions
    //   3.0 → loft conversion
    //   3.5 → side extension
    //   4.0 → rear extension
    //   4.6 → rotation kicks in
    const stageStarts = [0.0, 0.4, 0.9, 1.4, 1.9, 2.2, 3.0, 3.5, 4.0];
    const stageDur = 0.55;
    const dimStart = 2.5;
    const dimDur = 0.6;

    // If reduced motion: show all instantly
    if (prefersReducedMotion) {
        stages.forEach((s) => setStageOpacity(s, 1));
        setStageOpacity(dimGroup, 1);
        labelEls.forEach((el) => (el.style.opacity = '1'));
        axisLabels.forEach((el) => (el.style.opacity = '1'));
        extLabelEls.forEach((el) => (el.style.opacity = '1'));
    }

    function setStageOpacity(stage, p) {
        const tgt = stage.userData.targetOpacity ?? 1;
        stage.traverse((obj) => {
            if (obj.isMesh) {
                if (obj.material) {
                    obj.material.opacity = (obj.userData.targetOpacity ?? tgt) * p;
                    obj.material.transparent = true;
                }
            }
            if (obj.isLine || obj.isLineSegments || obj.isLine2) {
                if (obj.material) {
                    obj.material.opacity = (obj.userData.targetOpacity ?? tgt) * p;
                    obj.material.transparent = true;
                }
            }
            if (obj.isGridHelper) {
                obj.material.opacity = (obj.userData.targetOpacity ?? 0.22) * p;
            }
        });
    }

    // Project a 3D point in houseGroup local space to screen coords
    const projVec = new THREE.Vector3();
    function project(point, sizeW, sizeH) {
        projVec.copy(point).applyMatrix4(houseGroup.matrixWorld).project(camera);
        return {
            x: (projVec.x * 0.5 + 0.5) * sizeW,
            y: (-projVec.y * 0.5 + 0.5) * sizeH,
            visible: projVec.z < 1,
        };
    }

    const clock = new THREE.Clock();
    function tick() {
        if (visible) {
            const t = clock.getElapsedTime();

            // Stage reveals
            if (!prefersReducedMotion) {
                stages.forEach((s, i) => {
                    const start = stageStarts[i];
                    const p = Math.min(1, Math.max(0, (t - start) / stageDur));
                    setStageOpacity(s, p);
                });

                // Dimensions
                const dp = Math.min(1, Math.max(0, (t - dimStart) / dimDur));
                setStageOpacity(dimGroup, dp);
                labelEls.forEach((el) => (el.style.opacity = dp > 0.4 ? '1' : '0'));
                axisLabels.forEach((el) => (el.style.opacity = dp > 0.2 ? '1' : '0'));

                // Extension labels fade in alongside their stage
                extensionLabels.forEach((l, i) => {
                    const sStart = stageStarts[l.stageIndex];
                    const p = Math.min(1, Math.max(0, (t - sStart) / stageDur));
                    extLabelEls[i].style.opacity = p > 0.3 ? '1' : '0';
                });
            }

            // Rotation only kicks in after structure + extensions are fully drawn
            const spinStart = 4.6;
            const spinT = Math.max(0, t - spinStart);
            houseGroup.rotation.y = spinT * 0.12;

            // Smooth mouse parallax (only when crosshair active)
            mouse.x += (mouse.tx - mouse.x) * 0.04;
            mouse.y += (mouse.ty - mouse.y) * 0.04;
            camera.position.x = 9.5 + mouse.x * 0.7;
            camera.position.y = 6 + mouse.y * 0.35;
            camera.lookAt(cameraTarget);

            renderer.render(scene, camera);

            // Position HTML labels via projection (uses world matrices computed in render)
            const w = container.clientWidth;
            const h = container.clientHeight;
            labels.forEach((l, i) => {
                const p = project(l.anchor, w, h);
                labelEls[i].style.left = p.x + 'px';
                labelEls[i].style.top  = p.y + 'px';
            });
            extensionLabels.forEach((l, i) => {
                const p = project(l.anchor, w, h);
                extLabelEls[i].style.left = p.x + 'px';
                extLabelEls[i].style.top  = p.y + 'px';
            });

            // Gizmo (overlay in lower-left)
            const gizmoSize = Math.min(70, w * 0.16);
            renderer.setScissorTest(true);
            renderer.setScissor(16, 16, gizmoSize, gizmoSize);
            renderer.setViewport(16, 16, gizmoSize, gizmoSize);
            // Rotate gizmo to match houseGroup rotation (visualizing world XYZ)
            gizmo.quaternion.copy(houseGroup.quaternion).invert();
            // Camera looks straight; we instead position gizmo to face camera
            gizmo.rotation.x = -0.4;
            gizmo.rotation.y = -houseGroup.rotation.y + 0.4;
            renderer.render(gizmoScene, gizmoCam);
            renderer.setScissorTest(false);
            renderer.setViewport(0, 0, w, h);

            // Axis label positions (gizmo origin is at canvas (16+gizmoSize/2, h-16-gizmoSize/2))
            const cx = 16 + gizmoSize / 2;
            const cy = h - 16 - gizmoSize / 2;
            const axisLen = gizmoSize * 0.42;
            // X axis end (rotated by houseGroup.rotation.y around Y)
            const ry = -houseGroup.rotation.y + 0.4;
            const rx = -0.4;
            // simple manual projection: rotate (1,0,0), (0,1,0), (0,0,1) by rx then ry, ignore z
            const proj = (vx, vy, vz) => {
                // rotate around X (rx)
                let y1 = vy * Math.cos(rx) - vz * Math.sin(rx);
                let z1 = vy * Math.sin(rx) + vz * Math.cos(rx);
                let x1 = vx;
                // rotate around Y (ry)
                let x2 = x1 * Math.cos(ry) + z1 * Math.sin(ry);
                let z2 = -x1 * Math.sin(ry) + z1 * Math.cos(ry);
                let y2 = y1;
                return { x: x2, y: y2 };
            };
            const px = proj(1, 0, 0);
            const py = proj(0, 1, 0);
            const pz = proj(0, 0, 1);
            axisLabels[0].style.left = (cx + px.x * axisLen + 8) + 'px';
            axisLabels[0].style.top  = (cy - px.y * axisLen) + 'px';
            axisLabels[1].style.left = (cx + py.x * axisLen) + 'px';
            axisLabels[1].style.top  = (cy - py.y * axisLen - 8) + 'px';
            axisLabels[2].style.left = (cx + pz.x * axisLen) + 'px';
            axisLabels[2].style.top  = (cy - pz.y * axisLen) + 'px';

            // Crosshair lines
            if (mouse.inside) {
                chH.style.top = chY + 'px';
                chV.style.left = chX + 'px';
                chC.style.left = chX + 'px';
                chC.style.top  = chY + 'px';
                chCoord.style.left = (chX + 14) + 'px';
                chCoord.style.top  = (chY + 14) + 'px';
                // Fake world coords for fun
                const xCoord = ((chX / w - 0.5) * 12).toFixed(2);
                const yCoord = ((0.5 - chY / h) * 8).toFixed(2);
                chCoord.textContent = `X ${xCoord}  Y ${yCoord}`;
            }

        }
        requestAnimationFrame(tick);
    }

    // Initialize stages invisible (except snap markers which need targetOpacity assignment)
    stages.forEach((s) => setStageOpacity(s, 0));
    setStageOpacity(dimGroup, 0);

    requestAnimationFrame(tick);
}

// ── Scene 2: Ambient field — drifting "+" markers + dashed construction lines
export function initAmbientField(container) {
    if (!container || prefersReducedMotion) return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(60, 1, 0.1, 100);
    camera.position.z = 14;

    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);
    renderer.domElement.style.cssText = 'width:100%;height:100%;display:block;';

    // Crosshair "+" markers as LineSegments
    const CROSS_COUNT = 60;
    const crossPositions = [];
    for (let i = 0; i < CROSS_COUNT; i++) {
        const x = (Math.random() - 0.5) * 28;
        const y = (Math.random() - 0.5) * 16;
        const z = (Math.random() - 0.5) * 18;
        const s = 0.18;
        crossPositions.push(
            x - s, y, z,  x + s, y, z,   // horizontal
            x, y - s, z,  x, y + s, z,   // vertical
        );
    }
    const crossGeo = new THREE.BufferGeometry();
    crossGeo.setAttribute('position', new THREE.Float32BufferAttribute(crossPositions, 3));
    const crossMat = new THREE.LineBasicMaterial({
        color: PRIMARY, transparent: true, opacity: 0.32,
    });
    const crosses = new THREE.LineSegments(crossGeo, crossMat);
    scene.add(crosses);

    // Drifting dashed construction lines (long polylines)
    const constructionLines = new THREE.Group();
    for (let i = 0; i < 7; i++) {
        const startX = (Math.random() - 0.5) * 24;
        const startY = (Math.random() - 0.5) * 12;
        const z = (Math.random() - 0.5) * 8 - 2;
        const len = 6 + Math.random() * 8;
        const angle = Math.random() * Math.PI;
        const endX = startX + Math.cos(angle) * len;
        const endY = startY + Math.sin(angle) * len;
        const line = lineFromPoints(
            [new THREE.Vector3(startX, startY, z), new THREE.Vector3(endX, endY, z)],
            i % 2 === 0 ? PRIMARY : DIM,
            0.18,
            true,
        );
        line.userData.drift = (Math.random() - 0.5) * 0.0015;
        constructionLines.add(line);
    }
    scene.add(constructionLines);

    // Drifting wireframe boxes (small architectural elements)
    const boxes = new THREE.Group();
    for (let i = 0; i < 6; i++) {
        const size = 0.5 + Math.random() * 0.8;
        const box = solidEdges(new THREE.BoxGeometry(size, size, size), i % 2 === 0 ? PRIMARY : ACCENT, 0.22);
        box.position.set(
            (Math.random() - 0.5) * 22,
            (Math.random() - 0.5) * 12,
            (Math.random() - 0.5) * 10 - 3,
        );
        box.userData = {
            rx: (Math.random() - 0.5) * 0.4,
            ry: (Math.random() - 0.5) * 0.4,
            phase: Math.random() * Math.PI * 2,
        };
        boxes.add(box);
    }
    scene.add(boxes);

    // Camera parallax
    const mouse = { x: 0, y: 0, tx: 0, ty: 0 };
    window.addEventListener('mousemove', (e) => {
        mouse.tx = (e.clientX / window.innerWidth - 0.5) * 2;
        mouse.ty = (e.clientY / window.innerHeight - 0.5) * 2;
    }, { passive: true });

    const resize = () => {
        const w = container.clientWidth;
        const h = container.clientHeight;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
    };
    resize();
    new ResizeObserver(resize).observe(container);

    let visible = true;
    new IntersectionObserver(
        ([entry]) => { visible = entry.isIntersecting; },
        { threshold: 0.01 }
    ).observe(container);

    const clock = new THREE.Clock();
    function tick() {
        const t = clock.getElapsedTime();
        if (visible) {
            // Slow drift up
            crosses.position.y = ((t * 0.15) % 4) - 2;

            constructionLines.children.forEach((l) => {
                l.position.x += l.userData.drift;
                if (l.position.x > 8) l.position.x = -8;
                if (l.position.x < -8) l.position.x = 8;
            });

            boxes.children.forEach((b) => {
                b.rotation.x += b.userData.rx * 0.005;
                b.rotation.y += b.userData.ry * 0.005;
                b.position.y += Math.sin(t * 0.4 + b.userData.phase) * 0.002;
            });

            mouse.x += (mouse.tx - mouse.x) * 0.03;
            mouse.y += (mouse.ty - mouse.y) * 0.03;
            camera.position.x = mouse.x * 0.8;
            camera.position.y = -mouse.y * 0.5;
            camera.lookAt(0, 0, 0);

            renderer.render(scene, camera);
        }
        requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
}
