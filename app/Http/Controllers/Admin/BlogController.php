<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\Tag;
use App\Repositories\BlogRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = BlogRepository::query()->with('tags', 'category', 'user', 'views')->paginate(20);

        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        $categories = CategoryRepository::query()->active()->get();

        $tags = Tag::latest('id')->take(150)->get();

        return view('admin.blog.create', compact('categories', 'tags'));
    }

    public function store(BlogRequest $request)
    {
        BlogRepository::storeByRequest($request);

        return to_route('admin.blog.index')->withSuccess(__('Created successfully'));
    }

    public function edit(Blog $blog)
    {
        $categories = CategoryRepository::query()->active()->get();

        $blogTags = $blog->tags()?->pluck('id')->toArray() ?? [];
        $mandatoryTags = Tag::whereIn('id', $blogTags)->get();

        // Calculate tags are needed to reach 150
        $remainingCount = 150 - $mandatoryTags->count();

        $additionalTags = Tag::latest()
            ->whereNotIn('id', $blogTags)
            ->take($remainingCount)
            ->get();
        $tags = $mandatoryTags->merge($additionalTags);

        return view('admin.blog.edit', compact('blog', 'categories', 'tags', 'blogTags'));
    }

    public function update(BlogRequest $request, Blog $blog)
    {
        BlogRepository::updateByRequest($request, $blog);

        return to_route('admin.blog.index')->withSuccess(__('Updated successfully'));
    }

    public function statusToggle(Blog $blog)
    {
        $blog->update([
            'is_active' => ! $blog->is_active,
        ]);

        return to_route('admin.blog.index')->withSuccess(__('Status Updated Successfully'));
    }

    public function destroy(Blog $blog)
    {
        $media = $blog->media;
        if ($media && Storage::exists($media?->src)) {
            Storage::delete($media->src);
        }

        $blog->tags()->detach();

        $blog->delete();

        if ($media) {
            $media->delete();
        }

        return to_route('admin.blog.index')->withSuccess(__('Deleted successfully'));
    }
}
