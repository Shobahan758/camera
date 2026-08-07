<?php

namespace App\Http\Controllers;

use App\Models\LandingSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingSectionController extends Controller
{
    public function index(): View
    {
        $definitions = array_diff_key(LandingSection::definitions(), array_flip(['site', 'social', 'seo']));
        $sections = LandingSection::whereIn('slug', array_keys($definitions))->get()->keyBy('slug');

        return view('dasgboard.pages.site-sections', compact('definitions', 'sections'));
    }

    public function updateVisibility(Request $request): RedirectResponse
    {
        $slugs = array_values(array_diff(array_keys(LandingSection::definitions()), ['site', 'social', 'seo']));
        $validated = $request->validate([
            'active_sections' => ['nullable', 'array'],
            'active_sections.*' => ['string', 'in:'.implode(',', $slugs)],
        ]);
        $active = $validated['active_sections'] ?? [];

        foreach ($slugs as $slug) {
            LandingSection::updateOrCreate(
                ['slug' => $slug],
                ['content' => LandingSection::where('slug', $slug)->value('content') ?? LandingSection::defaults($slug), 'is_visible' => in_array($slug, $active, true)],
            );
        }

        return back()->with('success', 'সব বিভাগের অবস্থা সফলভাবে আপডেট হয়েছে।');
    }

    public function edit(string $section): View
    {
        $definition = LandingSection::definitions()[$section] ?? abort(404);

        $record = LandingSection::firstOrNew(['slug' => $section]);
        $record->content = array_merge(LandingSection::defaults($section), $record->content ?? []);
        if ($definition['dynamic_reviews'] ?? false) $record->content = array_merge($record->content, ['reviews' => LandingSection::reviewItems($record->content)]);

        return view('dasgboard.pages.landing-section', ['slug' => $section, 'definition' => $definition, 'section' => $record]);
    }

    public function update(Request $request, string $section): RedirectResponse
    {
        $definition = LandingSection::definitions()[$section] ?? abort(404);
        $rules = ['is_visible' => ['nullable', 'boolean'], 'image' => ['nullable', 'image', 'max:4096']];
        for ($i = 1; $i <= ($definition['images'] ?? 0); $i++) $rules["image_{$i}"] = ['nullable', 'image', 'max:4096'];
        foreach ($definition['fields'] as $key => [, $type]) $rules[$key] = ['nullable', $type === 'number' ? 'numeric' : 'string'];
        if ($definition['dynamic_reviews'] ?? false) {
            $rules += [
                'reviews' => ['required', 'array', 'min:1'],
                'reviews.*.rating' => ['required', 'string', 'max:10'],
                'reviews.*.text' => ['required', 'string', 'max:1000'],
                'reviews.*.avatar' => ['nullable', 'string', 'max:10'],
                'reviews.*.name' => ['required', 'string', 'max:100'],
                'reviews.*.location' => ['nullable', 'string', 'max:100'],
            ];
        }
        $validated = $request->validate($rules);
        $record = LandingSection::firstOrNew(['slug' => $section]);
        $content = $record->content ?? [];
        foreach (array_keys($definition['fields']) as $key) $content[$key] = $validated[$key] ?? null;
        if ($definition['dynamic_reviews'] ?? false) $content['reviews'] = array_values($validated['reviews']);
        if ($request->hasFile('image')) $content['image'] = $request->file('image')->store('landing', 'public');
        for ($i = 1; $i <= ($definition['images'] ?? 0); $i++) {
            if ($request->hasFile("image_{$i}")) $content["image_{$i}"] = $request->file("image_{$i}")->store('landing/gallery', 'public');
        }
        $record->fill(['content' => $content, 'is_visible' => $request->boolean('is_visible')])->save();

        return back()->with('success', 'বিভাগটি সফলভাবে আপডেট হয়েছে।');
    }
}
