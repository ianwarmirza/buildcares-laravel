<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PortfolioItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $items = PortfolioItem::orderBy('sort_order')->paginate(20);
        return view('admin.portfolio.index', compact('items'));
    }

    public function create()
    {
        $categories = PortfolioItem::$categories;
        return view('admin.portfolio.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|in:' . implode(',', array_keys(PortfolioItem::$categories)),
            'description'  => 'nullable|string',
            'location'     => 'nullable|string|max:255',
            'year'         => 'nullable|integer|min:2000|max:2030',
            'client'       => 'nullable|string|max:255',
            'cover_image'  => 'required|file|mimes:jpeg,png,jpg,webp,pdf|max:20480',
            'gallery_images.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:20480',
            'featured'     => 'nullable|boolean',
            'is_active'    => 'nullable|boolean',
            'sort_order'   => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = $this->makeUniqueSlug($validated['title']);
        $validated['cover_image'] = $this->storeFileConvertingPdfIfNeeded($request->file('cover_image'), 'portfolio');
        $validated['featured']  = $request->boolean('featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $gallery = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $img) {
                $gallery[] = $this->storeFileConvertingPdfIfNeeded($img, 'portfolio/gallery');
            }
        }
        $validated['gallery_images'] = $gallery;

        PortfolioItem::create($validated);

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item created.');
    }

    public function quickUpload(Request $request)
    {
        $validated = $request->validate([
            'category'    => 'required|string|in:' . implode(',', array_keys(PortfolioItem::$categories)),
            'file'        => 'required|file|mimes:jpeg,png,jpg,webp,pdf|max:20480',
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $title = !empty($validated['title']) ? $validated['title'] : Str::title(str_replace(['-', '_'], ' ', $originalName));

        $path = $this->storeFileConvertingPdfIfNeeded($file, 'portfolio');

        PortfolioItem::create([
            'title'       => $title,
            'slug'        => $this->makeUniqueSlug($title),
            'category'    => $validated['category'],
            'description' => $validated['description'] ?? null,
            'cover_image' => $path,
            'is_active'   => true,
            'sort_order'  => 0,
        ]);

        $categoryLabel = PortfolioItem::$categories[$validated['category']] ?? $validated['category'];

        return redirect()->back()->with('success', "Item uploaded successfully to \"{$categoryLabel}\"!");
    }

    public function edit(PortfolioItem $portfolio)
    {
        $categories = PortfolioItem::$categories;
        return view('admin.portfolio.form', ['item' => $portfolio, 'categories' => $categories]);
    }

    public function update(Request $request, PortfolioItem $portfolio)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|in:' . implode(',', array_keys(PortfolioItem::$categories)),
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'year'        => 'nullable|integer|min:2000|max:2030',
            'client'      => 'nullable|string|max:255',
            'cover_image' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:20480',
            'gallery_images.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:20480',
            'featured'    => 'nullable|boolean',
            'is_active'   => 'nullable|boolean',
            'sort_order'  => 'nullable|integer|min:0',
            'remove_gallery' => 'nullable|array',
            'remove_gallery.*' => 'string',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($portfolio->cover_image) {
                Storage::disk('public')->delete($portfolio->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('portfolio', 'public');
        } else {
            unset($validated['cover_image']);
        }

        $gallery = $portfolio->gallery_images ?? [];

        // Remove flagged gallery images.
        if ($remove = $request->input('remove_gallery', [])) {
            foreach ($remove as $path) {
                if (in_array($path, $gallery, true)) {
                    Storage::disk('public')->delete($path);
                    $gallery = array_values(array_filter($gallery, fn ($p) => $p !== $path));
                }
            }
        }

        // Append newly uploaded gallery images.
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $img) {
                $gallery[] = $img->store('portfolio/gallery', 'public');
            }
        }
        $validated['gallery_images'] = $gallery;
        unset($validated['remove_gallery']);

        $validated['featured']   = $request->boolean('featured');
        $validated['is_active']  = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $portfolio->update($validated);

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item updated.');
    }

    public function destroy(PortfolioItem $portfolio)
    {
        if ($portfolio->cover_image) {
            Storage::disk('public')->delete($portfolio->cover_image);
        }
        foreach (($portfolio->gallery_images ?? []) as $path) {
            Storage::disk('public')->delete($path);
        }
        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio item deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('admin.portfolio.index')->with('error', 'No portfolio items selected.');
        }

        $items = PortfolioItem::whereIn('id', $ids)->get();
        $count = 0;
        foreach ($items as $item) {
            if ($item->cover_image) {
                Storage::disk('public')->delete($item->cover_image);
            }
            foreach (($item->gallery_images ?? []) as $path) {
                Storage::disk('public')->delete($path);
            }
            $item->delete();
            $count++;
        }

        return redirect()->route('admin.portfolio.index')->with('success', "{$count} portfolio items deleted.");
    }

    private function makeUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (PortfolioItem::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    private function storeFileConvertingPdfIfNeeded(\Illuminate\Http\UploadedFile $file, string $folder = 'portfolio'): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'pdf' && extension_loaded('imagick') && class_exists('\Imagick')) {
            try {
                $imagick = new \Imagick();
                $imagick->setResolution(200, 200);
                $imagick->readImage($file->getRealPath() . '[0]');
                $imagick->setImageFormat('jpg');
                $imagick->setImageCompressionQuality(85);

                $filename = $folder . '/' . Str::uuid() . '.jpg';
                Storage::disk('public')->put($filename, $imagick->getImageBlob());
                $imagick->clear();
                $imagick->destroy();

                @mkdir(public_path('storage/' . $folder), 0777, true);
                @copy(storage_path('app/public/' . $filename), public_path('storage/' . $filename));

                return $filename;
            } catch (\Throwable $e) {
                // Fallback to standard file storage
            }
        }

        $path = $file->store($folder, 'public');
        @mkdir(public_path('storage/' . $folder), 0777, true);
        @copy(storage_path('app/public/' . $path), public_path('storage/' . $path));
        return $path;
    }
}
