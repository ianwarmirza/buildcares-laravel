// BuildCares hero 3D — UK Architectural 3D CAD Visualization Scene
//   • Typical UK Semi-Detached House with 3 Key Architectural Features:
//     1) Single-Storey Rear Extension (Bi-fold doors + Roof Lantern)
//     2) Double-Storey Side Extension (Matching brick & roofline)
//     3) Loft Conversion (Dormer Window + Velux Rooflights)
//   • Solid Shaded Meshes + Architectural CAD Line Overlays
//   • Dynamic Feature Badges (Loft, Side Ext, Rear Ext)
//   • Dimension Lines with Arrowheads + Measurement Badges
//   • XYZ Axis Gizmo + AutoCAD Crosshair Cursor

import * as THREE from 'three';

const PRIMARY = 0x2563eb; // Main CAD blue
const ACCENT  = 0x0ea5e9; // Openings/Windows
const DIM     = 0xf59e0b; // CAD Dimension Amber
const HIDDEN  = 0x94a3b8; // Construction Lines
const SOFT    = 0xbfdbfe; // Accent Soft Blue

// Extension Layer Colors
const LOFT_C  = 0x10b981; // Emerald - Loft Conversion
const SIDE_C  = 0xa855f7; // Purple  - Double Storey Side Extension
const REAR_C  = 0xf97316; // Orange  - Single Storey Rear Extension

// Architectural Materials Colors
const BRICK_C  = 0xc26d47; // UK Red/Buff Brick
const ROOF_C   = 0x334155; // UK Dark Slate Roof
const GLASS_C  = 0x93c5fd; // Glazing / Windows
const FRAME_C  = 0x1e293b; // Anthracite Window Frames
const WALL_ALT = 0xe2e8f0; // Smooth Render Extension Wall

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// ── Generic line & mesh helpers ──────────────────────────────────────────────
function solidEdges(geometry, color, opacity = 1) {
    const edges = new THREE.EdgesGeometry(geometry, 1);
    const mat = new THREE.LineBasicMaterial({
        color,
        transparent: true,
        opacity,
    });
    return new THREE.LineSegments(edges, mat);
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

function filledMesh(geometry, color, opacity = 0.85) {
    const mat = new THREE.MeshLambertMaterial({
        color,
        transparent: true,
        opacity,
        side: THREE.DoubleSide,
    });
    const mesh = new THREE.Mesh(geometry, mat);
    mesh.userData.targetOpacity = opacity;
    return mesh;
}

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

function dimensionLine(from, to, normal, offset, color = DIM) {
    const group = new THREE.Group();
    const fromOff = from.clone().addScaledVector(normal, offset);
    const toOff   = to.clone().addScaledVector(normal, offset);

    group.add(lineFromPoints(
        [from.clone().addScaledVector(normal, offset * 0.1), fromOff.clone().addScaledVector(normal, 0.15)],
        color, 0
    ));
    group.add(lineFromPoints(
        [to.clone().addScaledVector(normal, offset * 0.1), toOff.clone().addScaledVector(normal, 0.15)],
        color, 0
    ));

    group.add(lineFromPoints([fromOff, toOff], color, 0));

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

    const labelAnchor = fromOff.clone().add(toOff).multiplyScalar(0.5).addScaledVector(normal, 0.15);
    return { group, labelAnchor };
}

function axisGizmo() {
    const group = new THREE.Group();
    const len = 0.5;
    group.add(lineFromPoints([new THREE.Vector3(0,0,0), new THREE.Vector3(len,0,0)], 0xef4444, 0.95));
    group.add(lineFromPoints([new THREE.Vector3(0,0,0), new THREE.Vector3(0,len,0)], 0x22c55e, 0.95));
    group.add(lineFromPoints([new THREE.Vector3(0,0,0), new THREE.Vector3(0,0,len)], 0x3b82f6, 0.95));
    return group;
}

// ── Build house stages ───────────────────────────────────────────────────────
function buildHouseStages() {
    const stages = [];
    const extensionLabels = [];

    // Stage 0: Ground footprint & CAD grid
    const plate = new THREE.Group();
    const footprint = lineFromPoints([
        new THREE.Vector3(-2.5, 0, -2),
        new THREE.Vector3( 4.5, 0, -2),
        new THREE.Vector3( 4.5, 0,  2),
        new THREE.Vector3(-2.5, 0,  2),
        new THREE.Vector3(-2.5, 0, -2),
    ], PRIMARY, 0, true);
    plate.add(footprint);

    const grid = new THREE.GridHelper(12, 24, SOFT, SOFT);
    grid.material.transparent = true;
    grid.material.opacity = 0;
    grid.userData.targetOpacity = 0.25;
    plate.add(grid);
    plate.userData.targetOpacity = 0.85;
    stages.push(plate);

    // Stage 1: Main House Walls (Brick mesh + CAD wireframe)
    const walls = new THREE.Group();
    const mainWallGeo = new THREE.BoxGeometry(4, 2.2, 3);
    const mainWallMesh = filledMesh(mainWallGeo, BRICK_C, 0.75);
    mainWallMesh.position.y = 1.1;
    walls.add(mainWallMesh);

    const wallBoxLines = solidEdges(mainWallGeo, PRIMARY, 0);
    wallBoxLines.position.y = 1.1;
    walls.add(wallBoxLines);
    walls.userData.targetOpacity = 0.95;
    stages.push(walls);

    // Stage 2: Main Roof (Gable ends on sides, roof slopes facing front and back)
    const roof = new THREE.Group();
    const roofShape = new THREE.Shape();
    roofShape.moveTo(-1.625, 0);  // Rear eave
    roofShape.lineTo(1.625, 0);   // Front eave
    roofShape.lineTo(0, 1.45);    // Central ridge peak
    roofShape.closePath();

    const roofGeo = new THREE.ExtrudeGeometry(roofShape, { depth: 4.35, bevelEnabled: false });
    roofGeo.rotateY(Math.PI / 2);
    roofGeo.translate(-2.175, 2.2, 0);

    const roofMesh = filledMesh(roofGeo, ROOF_C, 0.85);
    roof.add(roofMesh);

    const roofEdges = solidEdges(roofGeo, PRIMARY, 0);
    roof.add(roofEdges);

    // Main roof ridge line (running left-to-right along X-axis)
    roof.add(lineFromPoints([
        new THREE.Vector3(-2.175, 3.65, 0),
        new THREE.Vector3( 2.175, 3.65, 0),
    ], PRIMARY, 0));

    roof.userData.targetOpacity = 0.95;
    stages.push(roof);

    // Stage 3: Openings & Centralized Front Door with Hipped Roof Porch
    const openings = new THREE.Group();

    // Central Entrance Door (centered at X = 0.0)
    const doorMesh = filledMesh(new THREE.BoxGeometry(0.7, 1.35, 0.05), FRAME_C, 0.9);
    doorMesh.position.set(0.0, 0.675, 1.51);
    openings.add(doorMesh);
    openings.add(solidEdges(new THREE.BoxGeometry(0.7, 1.35, 0.05), ACCENT, 0).position.set(0.0, 0.675, 1.51));

    // Porch side brick pillars & architrave beam
    const leftPillar = filledMesh(new THREE.BoxGeometry(0.12, 1.35, 0.55), BRICK_C, 0.85);
    leftPillar.position.set(-0.54, 0.675, 1.775);
    openings.add(leftPillar);
    openings.add(solidEdges(new THREE.BoxGeometry(0.12, 1.35, 0.55), PRIMARY, 0).position.set(-0.54, 0.675, 1.775));

    const rightPillar = filledMesh(new THREE.BoxGeometry(0.12, 1.35, 0.55), BRICK_C, 0.85);
    rightPillar.position.set(0.54, 0.675, 1.775);
    openings.add(rightPillar);
    openings.add(solidEdges(new THREE.BoxGeometry(0.12, 1.35, 0.55), PRIMARY, 0).position.set(0.54, 0.675, 1.775));

    const porchBeam = filledMesh(new THREE.BoxGeometry(1.2, 0.12, 0.55), ROOF_C, 0.9);
    porchBeam.position.set(0.0, 1.36, 1.775);
    openings.add(porchBeam);
    openings.add(solidEdges(new THREE.BoxGeometry(1.2, 0.12, 0.55), PRIMARY, 0).position.set(0.0, 1.36, 1.775));

    // Hipped Roof above Porch (3-sided pitch sloping up to wall)
    const porchRoofGeo = new THREE.BufferGeometry();
    const pVertices = new Float32Array([
        -0.65, 1.42, 1.50,  // 0: Rear-Left eave against wall
        +0.65, 1.42, 1.50,  // 1: Rear-Right eave against wall
        +0.65, 1.42, 2.08,  // 2: Front-Right eave
        -0.65, 1.42, 2.08,  // 3: Front-Left eave
         0.00, 1.82, 1.50,  // 4: Wall Top Ridge Peak
         0.00, 1.62, 1.85,  // 5: Front Hip Ridge Point
    ]);

    const pIndices = [
        0, 3, 5,  0, 5, 4, // Left slope
        1, 4, 5,  1, 5, 2, // Right slope
        3, 2, 5           // Front slope
    ];

    porchRoofGeo.setAttribute('position', new THREE.BufferAttribute(pVertices, 3));
    porchRoofGeo.setIndex(pIndices);
    porchRoofGeo.computeVertexNormals();

    const porchRoofMesh = filledMesh(porchRoofGeo, ROOF_C, 0.9);
    openings.add(porchRoofMesh);

    // Hipped Porch Roof CAD Edges
    const porchEdgePairs = [
        [[-0.65, 1.42, 1.50], [-0.65, 1.42, 2.08]], // Left eave
        [[-0.65, 1.42, 2.08], [+0.65, 1.42, 2.08]], // Front eave
        [[+0.65, 1.42, 2.08], [+0.65, 1.42, 1.50]], // Right eave
        [[-0.65, 1.42, 1.50], [ 0.00, 1.82, 1.50]], // Wall left hip
        [[+0.65, 1.42, 1.50], [ 0.00, 1.82, 1.50]], // Wall right hip
        [[-0.65, 1.42, 2.08], [ 0.00, 1.62, 1.85]], // Left hip ridge
        [[+0.65, 1.42, 2.08], [ 0.00, 1.62, 1.85]], // Right hip ridge
        [[ 0.00, 1.82, 1.50], [ 0.00, 1.62, 1.85]], // Main ridge
    ];

    porchEdgePairs.forEach(([p1, p2]) => {
        openings.add(lineFromPoints([
            new THREE.Vector3(...p1),
            new THREE.Vector3(...p2)
        ], PRIMARY, 0));
    });

    // Helper for Ground Floor Bi-Fold Door (Wide glass folding panels with vertical mullions)
    function createBiFoldDoor(xPos, yPos, zPos, width = 1.35, height = 1.1) {
        const group = new THREE.Group();

        // Dark Aluminum Outer Frame
        const frameGeo = new THREE.BoxGeometry(width, height, 0.06);
        const frameMesh = filledMesh(frameGeo, FRAME_C, 0.95);
        group.add(frameMesh);
        group.add(solidEdges(frameGeo, ACCENT, 0));

        // Full Glazing Glass Panel
        const glassGeo = new THREE.BoxGeometry(width - 0.08, height - 0.08, 0.04);
        const glassMesh = filledMesh(glassGeo, GLASS_C, 0.85);
        group.add(glassMesh);

        // Vertical Bifold Door Folding Mullions (3 folding panels)
        const panelW = (width - 0.08) / 3;
        for (let i = 1; i < 3; i++) {
            const xOff = -(width - 0.08) / 2 + i * panelW;
            group.add(lineFromPoints([
                new THREE.Vector3(xOff, -(height - 0.08) / 2, 0.035),
                new THREE.Vector3(xOff, (height - 0.08) / 2, 0.035),
            ], ACCENT, 0));
        }

        group.position.set(xPos, yPos, zPos);
        return group;
    }

    // Helper for Architectural Double Casement Window (Frame + Glass + Center Mullion + Transom line)
    function createArchitecturalWindow(xPos, yPos, zPos, width = 0.85, height = 0.85) {
        const group = new THREE.Group();

        // Dark Frame
        const frameGeo = new THREE.BoxGeometry(width, height, 0.06);
        const frameMesh = filledMesh(frameGeo, FRAME_C, 0.95);
        group.add(frameMesh);
        group.add(solidEdges(frameGeo, ACCENT, 0));

        // Glass Panes
        const glassGeo = new THREE.BoxGeometry(width - 0.08, height - 0.08, 0.04);
        const glassMesh = filledMesh(glassGeo, GLASS_C, 0.8);
        group.add(glassMesh);

        // Center Vertical Mullion Bar
        group.add(lineFromPoints([
            new THREE.Vector3(0, -(height - 0.08) / 2, 0.035),
            new THREE.Vector3(0, (height - 0.08) / 2, 0.035),
        ], ACCENT, 0));

        // Horizontal Transom Bar
        group.add(lineFromPoints([
            new THREE.Vector3(-(width - 0.08) / 2, (height - 0.08) * 0.15, 0.035),
            new THREE.Vector3((width - 0.08) / 2, (height - 0.08) * 0.15, 0.035),
        ], ACCENT, 0));

        group.position.set(xPos, yPos, zPos);
        return group;
    }

    // Ground Floor Left: Architectural Window (Same design and size as above floor)
    openings.add(createArchitecturalWindow(-1.25, 0.65, 1.51, 0.85, 0.85));

    // Ground Floor Right: Architectural Window
    openings.add(createArchitecturalWindow(1.25, 0.65, 1.51, 0.85, 0.85));

    // First Floor Left: Architectural Window
    openings.add(createArchitecturalWindow(-1.25, 1.65, 1.51, 0.85, 0.85));

    // First Floor Right: Architectural Window
    openings.add(createArchitecturalWindow(1.25, 1.65, 1.51, 0.85, 0.85));

    openings.userData.targetOpacity = 0.9;
    stages.push(openings);

    // Stage 4: Hidden structural line overlays for CAD look
    const hidden = new THREE.Group();
    const hiddenBox = dashedEdges(new THREE.BoxGeometry(4, 2.2, 3), HIDDEN, 0, 0.08, 0.08);
    hiddenBox.position.y = 1.1;
    hidden.add(hiddenBox);
    hidden.userData.targetOpacity = 0.25;
    stages.push(hidden);

    // Stage 5: Corner snap markers
    const snaps = new THREE.Group();
    const corners = [
        new THREE.Vector3(-2, 0,  1.5), new THREE.Vector3( 2, 0,  1.5),
        new THREE.Vector3(-2, 0, -1.5), new THREE.Vector3( 2, 0, -1.5),
        new THREE.Vector3(-2, 2.2,  1.5), new THREE.Vector3( 2, 2.2,  1.5),
        new THREE.Vector3(-2, 2.2, -1.5), new THREE.Vector3( 2, 2.2, -1.5),
        new THREE.Vector3(-2.175, 3.65, 0), new THREE.Vector3(2.175, 3.65, 0),
    ];
    corners.forEach((c) => snaps.add(snapMarker(c, ACCENT, 0.14)));
    snaps.userData.targetOpacity = 0.9;
    stages.push(snaps);

    // Stage 6: 🟢 Loft Conversion (Rear Box Dormer on rear roof slope + Velux on front slope)
    const loft = new THREE.Group();

    // Box Dormer sitting on the rear slope (facing -Z)
    const dormerGeo = new THREE.BoxGeometry(1.6, 0.85, 1.1);
    const dormerMesh = filledMesh(dormerGeo, ROOF_C, 0.9);
    dormerMesh.position.set(0.2, 2.95, -0.85);
    loft.add(dormerMesh);

    const dormerEdges = solidEdges(dormerGeo, LOFT_C, 0);
    dormerEdges.position.set(0.2, 2.95, -0.85);
    loft.add(dormerEdges);

    // Dormer rear window (facing -Z)
    const dormerWin = filledMesh(new THREE.BoxGeometry(1.2, 0.55, 0.05), GLASS_C, 0.85);
    dormerWin.position.set(0.2, 2.95, -1.41);
    loft.add(dormerWin);
    loft.add(solidEdges(new THREE.BoxGeometry(1.2, 0.55, 0.05), LOFT_C, 0).position.set(0.2, 2.95, -1.41));

    // Front Roof Velux Windows (flush-mounted against front roof pitch facing +Z)
    function createVeluxWindow(xPos, zPos) {
        const vGroup = new THREE.Group();
        const slopeAngle = Math.atan2(1.45, 1.625); // exact pitch angle of roof slope
        const yPos = 3.65 - (1.45 / 1.625) * zPos + 0.015; // height on front roof slope + slight offset above tiles

        // Velux outer frame
        const fGeo = new THREE.BoxGeometry(0.65, 0.85, 0.03);
        const fMesh = filledMesh(fGeo, FRAME_C, 0.95);
        vGroup.add(fMesh);
        vGroup.add(solidEdges(fGeo, LOFT_C, 0));

        // Translucent glass pane inset
        const gGeo = new THREE.BoxGeometry(0.53, 0.73, 0.04);
        const gMesh = filledMesh(gGeo, GLASS_C, 0.85);
        vGroup.add(gMesh);

        // Position on slope and tilt flush to roof pitch
        vGroup.position.set(xPos, yPos, zPos);
        vGroup.rotation.x = -slopeAngle;

        return vGroup;
    }

    loft.add(createVeluxWindow(-1.0, 0.75));
    loft.add(createVeluxWindow(-0.1, 0.75));

    loft.userData.targetOpacity = 0.95;
    stages.push(loft);
    extensionLabels.push({
        text: 'LOFT CONVERSION',
        subtext: 'Rear Box Dormer & Rooflights',
        color: '#10b981',
        anchor: new THREE.Vector3(0.2, 3.45, -0.85),
        stageIndex: 6,
    });

    // Stage 7: 🟣 Double-Storey Side Extension
    const sideExt = new THREE.Group();

    // Side Extension 2-storey walls
    const sideWallGeo = new THREE.BoxGeometry(2.3, 2.2, 3);
    const sideWallMesh = filledMesh(sideWallGeo, BRICK_C, 0.8);
    sideWallMesh.position.set(3.15, 1.1, 0);
    sideExt.add(sideWallMesh);
    const sideWallEdges = solidEdges(sideWallGeo, SIDE_C, 0);
    sideWallEdges.position.set(3.15, 1.1, 0);
    sideExt.add(sideWallEdges);

    // Pitched roof extending main roofline over side extension (slopes front/back, gable right)
    const sideRoofShape = new THREE.Shape();
    sideRoofShape.moveTo(-1.625, 0);
    sideRoofShape.lineTo(1.625, 0);
    sideRoofShape.lineTo(0, 1.45);
    sideRoofShape.closePath();

    const sideRoofGeo = new THREE.ExtrudeGeometry(sideRoofShape, { depth: 2.35, bevelEnabled: false });
    sideRoofGeo.rotateY(Math.PI / 2);
    sideRoofGeo.translate(1.95, 2.2, 0);
    const sideRoofMesh = filledMesh(sideRoofGeo, ROOF_C, 0.85);
    sideExt.add(sideRoofMesh);
    const sideRoofEdges = solidEdges(sideRoofGeo, SIDE_C, 0);
    sideExt.add(sideRoofEdges);

    // Windows on Front (+Z) matching main house architectural window style
    sideExt.add(createArchitecturalWindow(3.15, 0.65, 1.51, 0.85, 0.85));
    sideExt.add(createArchitecturalWindow(3.15, 1.65, 1.51, 0.85, 0.85));

    sideExt.userData.targetOpacity = 0.95;
    stages.push(sideExt);
    extensionLabels.push({
        text: 'DOUBLE-STOREY SIDE EXTENSION',
        subtext: 'Matching Brickwork & Roof',
        color: '#a855f7',
        anchor: new THREE.Vector3(3.6, 2.6, 0.8),
        stageIndex: 7,
    });

    // Stage 8: 🟠 Single-Storey Rear Extension (Bi-fold doors + Glass Roof Lantern)
    const rearExt = new THREE.Group();

    // Single storey extension wall
    const rearBoxGeo = new THREE.BoxGeometry(4.2, 1.1, 1.8);
    const rearMesh = filledMesh(rearBoxGeo, WALL_ALT, 0.85);
    rearMesh.position.set(0.1, 0.55, -2.4);
    rearExt.add(rearMesh);
    const rearEdges = solidEdges(rearBoxGeo, REAR_C, 0);
    rearEdges.position.set(0.1, 0.55, -2.4);
    rearExt.add(rearEdges);

    // Glass Roof Lantern on Flat Roof
    const lanternGeo = new THREE.BoxGeometry(2.0, 0.25, 1.0);
    const lanternMesh = filledMesh(lanternGeo, GLASS_C, 0.8);
    lanternMesh.position.set(0.1, 1.22, -2.4);
    rearExt.add(lanternMesh);
    rearExt.add(solidEdges(lanternGeo, REAR_C, 0).position.set(0.1, 1.22, -2.4));

    // Full-height modern aluminum Bi-Fold Glass Doors on -Z rear elevation
    const bifoldGeo = new THREE.BoxGeometry(3.2, 0.85, 0.05);
    const bifoldMesh = filledMesh(bifoldGeo, GLASS_C, 0.85);
    bifoldMesh.position.set(0.1, 0.5, -3.31);
    rearExt.add(bifoldMesh);
    rearExt.add(solidEdges(bifoldGeo, REAR_C, 0).position.set(0.1, 0.5, -3.31));

    // Vertical bi-fold door mullions
    [-1.0, -0.33, 0.33, 1.0].forEach((xOff) => {
        rearExt.add(lineFromPoints([
            new THREE.Vector3(0.1 + xOff, 0.08, -3.32),
            new THREE.Vector3(0.1 + xOff, 0.92, -3.32),
        ], REAR_C, 0));
    });

    rearExt.userData.targetOpacity = 0.95;
    stages.push(rearExt);
    extensionLabels.push({
        text: 'SINGLE-STOREY REAR EXTENSION',
        subtext: 'Bi-fold Doors & Roof Lantern',
        color: '#f97316',
        anchor: new THREE.Vector3(-0.8, 1.35, -2.5),
        stageIndex: 8,
    });

    return { stages };
}

// ── Build dimensions ─────────────────────────────────────────────────────────
function buildDimensions() {
    const dims = new THREE.Group();
    const labels = [];

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

    // Total Ridge Height (left side)
    {
        const d = dimensionLine(
            new THREE.Vector3(-2, 0, 1.5),
            new THREE.Vector3(-2, 3.65, 1.5),
            new THREE.Vector3(-1, 0, 0),
            1.0,
        );
        dims.add(d.group);
        labels.push({ anchor: d.labelAnchor, text: '5.40m' });
    }

    return { group: dims, labels };
}

// ── Main Scene Initialization ────────────────────────────────────────────────
export function initHeroHouse(container) {
    if (!container) return;

    const scene = new THREE.Scene();

    // Lighting setup for realistic shaded 3D rendering
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.85);
    scene.add(ambientLight);

    const mainLight = new THREE.DirectionalLight(0xffffff, 1.25);
    mainLight.position.set(12, 18, 10);
    scene.add(mainLight);

    const fillLight = new THREE.DirectionalLight(0x93c5fd, 0.45);
    fillLight.position.set(-10, 8, -10);
    scene.add(fillLight);

    const camera = new THREE.PerspectiveCamera(40, 1, 0.1, 100);
    camera.position.set(10.5, 7.0, 12.5);
    const cameraTarget = new THREE.Vector3(0.8, 1.5, -0.4);
    camera.lookAt(cameraTarget);

    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);
    renderer.domElement.style.cssText = 'width:100%;height:100%;display:block;';

    const houseGroup = new THREE.Group();
    scene.add(houseGroup);

    const { stages } = buildHouseStages();
    stages.forEach((s) => houseGroup.add(s));

    const { group: dimGroup, labels } = buildDimensions();
    dimGroup.userData.targetOpacity = 1;
    houseGroup.add(dimGroup);

    // HTML overlay layer for labels
    let labelLayer = container.querySelector('.cad-labels');
    if (!labelLayer) {
        labelLayer = document.createElement('div');
        labelLayer.className = 'cad-labels';
        labelLayer.style.cssText = 'position:absolute;inset:0;pointer-events:none;font-family:DM Sans,system-ui,sans-serif;z-index:20;';
        container.appendChild(labelLayer);
    }

    const labelEls = labels.map(({ text }) => {
        const el = document.createElement('div');
        el.textContent = text;
        el.style.cssText = `
            position:absolute; transform:translate(-50%,-50%);
            padding:2px 7px; font-size:9px; font-weight:700; letter-spacing:0.08em;
            color:#f59e0b; background:rgba(255,255,255,0.95); border:1px solid rgba(245,158,11,0.5);
            border-radius:3px; white-space:nowrap; opacity:0; transition:opacity 0.4s ease;
            box-shadow:0 2px 6px rgba(0,0,0,0.06);
        `;
        labelLayer.appendChild(el);
        return el;
    });

    // Axis gizmo (lower-left corner of the canvas — separate ortho scene)
    const gizmoScene = new THREE.Scene();
    const gizmoCam = new THREE.OrthographicCamera(-1, 1, 1, -1, 0.1, 10);
    gizmoCam.position.set(0, 0, 3);
    gizmoCam.lookAt(0, 0, 0);
    const gizmo = axisGizmo();
    gizmoScene.add(gizmo);

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

    // Crosshair cursor
    const crosshair = document.createElement('div');
    crosshair.className = 'cad-crosshair';
    crosshair.style.cssText = `
        position:absolute; inset:0; pointer-events:none; opacity:0; transition:opacity 0.3s ease; z-index:10;
    `;
    crosshair.innerHTML = `
        <div class="ch-h" style="position:absolute;left:0;right:0;height:1px;background:rgba(37,99,235,0.35);"></div>
        <div class="ch-v" style="position:absolute;top:0;bottom:0;width:1px;background:rgba(37,99,235,0.35);"></div>
        <div class="ch-c" style="position:absolute;width:18px;height:18px;transform:translate(-50%,-50%);">
            <div style="position:absolute;inset:0;border:1px solid rgba(37,99,235,0.55);background:rgba(255,255,255,0.4);"></div>
        </div>
        <div class="ch-coord" style="position:absolute;font-family:DM Sans,system-ui,sans-serif;font-size:9px;font-weight:600;color:#2563eb;background:rgba(255,255,255,0.9);padding:2px 6px;border:1px solid rgba(37,99,235,0.3);border-radius:3px;letter-spacing:0.05em;white-space:nowrap;"></div>
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

    let visible = true;
    const io = new IntersectionObserver(
        ([entry]) => { visible = entry.isIntersecting; },
        { threshold: 0.01 }
    );
    io.observe(container);

    const stageStarts = [0.0, 0.4, 0.9, 1.4, 1.9, 2.2, 3.0, 3.5, 4.0];
    const stageDur = 0.55;
    const dimStart = 2.5;
    const dimDur = 0.6;

    if (prefersReducedMotion) {
        stages.forEach((s) => setStageOpacity(s, 1));
        setStageOpacity(dimGroup, 1);
        labelEls.forEach((el) => (el.style.opacity = '1'));
        axisLabels.forEach((el) => (el.style.opacity = '1'));
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
                obj.material.opacity = (obj.userData.targetOpacity ?? 0.25) * p;
            }
        });
    }

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

            if (!prefersReducedMotion) {
                stages.forEach((s, i) => {
                    const start = stageStarts[i];
                    const p = Math.min(1, Math.max(0, (t - start) / stageDur));
                    setStageOpacity(s, p);
                });

                const dp = Math.min(1, Math.max(0, (t - dimStart) / dimDur));
                setStageOpacity(dimGroup, dp);
                labelEls.forEach((el) => (el.style.opacity = dp > 0.4 ? '1' : '0'));
                axisLabels.forEach((el) => (el.style.opacity = dp > 0.2 ? '1' : '0'));
            }

            const spinStart = 4.6;
            const spinT = Math.max(0, t - spinStart);
            houseGroup.rotation.y = spinT * 0.12;

            mouse.x += (mouse.tx - mouse.x) * 0.04;
            mouse.y += (mouse.ty - mouse.y) * 0.04;
            camera.position.x = 10.5 + mouse.x * 0.7;
            camera.position.y = 7.0 + mouse.y * 0.35;
            camera.lookAt(cameraTarget);

            renderer.render(scene, camera);

            const w = container.clientWidth;
            const h = container.clientHeight;
            labels.forEach((l, i) => {
                const p = project(l.anchor, w, h);
                labelEls[i].style.left = p.x + 'px';
                labelEls[i].style.top  = p.y + 'px';
            });

            const gizmoSize = Math.min(70, w * 0.16);
            renderer.setScissorTest(true);
            renderer.setScissor(16, 16, gizmoSize, gizmoSize);
            renderer.setViewport(16, 16, gizmoSize, gizmoSize);
            gizmo.quaternion.copy(houseGroup.quaternion).invert();
            gizmo.rotation.x = -0.4;
            gizmo.rotation.y = -houseGroup.rotation.y + 0.4;
            renderer.render(gizmoScene, gizmoCam);
            renderer.setScissorTest(false);
            renderer.setViewport(0, 0, w, h);

            const cx = 16 + gizmoSize / 2;
            const cy = h - 16 - gizmoSize / 2;
            const axisLen = gizmoSize * 0.42;
            const ry = -houseGroup.rotation.y + 0.4;
            const rx = -0.4;
            const proj = (vx, vy, vz) => {
                let y1 = vy * Math.cos(rx) - vz * Math.sin(rx);
                let z1 = vy * Math.sin(rx) + vz * Math.cos(rx);
                let x1 = vx;
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

            if (mouse.inside) {
                chH.style.top = chY + 'px';
                chV.style.left = chX + 'px';
                chC.style.left = chX + 'px';
                chC.style.top  = chY + 'px';
                chCoord.style.left = (chX + 14) + 'px';
                chCoord.style.top  = (chY + 14) + 'px';
                const xCoord = ((chX / w - 0.5) * 12).toFixed(2);
                const yCoord = ((0.5 - chY / h) * 8).toFixed(2);
                chCoord.textContent = `X ${xCoord}  Y ${yCoord}`;
            }
        }
        requestAnimationFrame(tick);
    }

    stages.forEach((s) => setStageOpacity(s, 0));
    setStageOpacity(dimGroup, 0);

    requestAnimationFrame(tick);
}

// ── Scene 2: Ambient background field ─────────────────────────────────────────
export function initAmbientField(container) {
    if (!container || prefersReducedMotion) return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(60, 1, 0.1, 100);
    camera.position.z = 14;

    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);
    renderer.domElement.style.cssText = 'width:100%;height:100%;display:block;';

    const CROSS_COUNT = 60;
    const crossPositions = [];
    for (let i = 0; i < CROSS_COUNT; i++) {
        const x = (Math.random() - 0.5) * 28;
        const y = (Math.random() - 0.5) * 16;
        const z = (Math.random() - 0.5) * 18;
        const s = 0.18;
        crossPositions.push(
            x - s, y, z,  x + s, y, z,
            x, y - s, z,  x, y + s, z,
        );
    }
    const crossGeo = new THREE.BufferGeometry();
    crossGeo.setAttribute('position', new THREE.Float32BufferAttribute(crossPositions, 3));
    const crossMat = new THREE.LineBasicMaterial({
        color: PRIMARY, transparent: true, opacity: 0.32,
    });
    const crosses = new THREE.LineSegments(crossGeo, crossMat);
    scene.add(crosses);

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

            renderer.render(scene, camera);
        }
        requestAnimationFrame(tick);
    }

    requestAnimationFrame(tick);
}
