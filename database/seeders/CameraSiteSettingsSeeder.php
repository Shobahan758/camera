<?php

namespace Database\Seeders;

use App\Models\LandingSection;
use Illuminate\Database\Seeder;

class CameraSiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        LandingSection::whereNotIn('slug', array_keys(LandingSection::definitions()))->delete();

        foreach (LandingSection::definitions() as $slug => $definition) {
            LandingSection::updateOrCreate(
                ['slug' => $slug],
                ['content' => LandingSection::defaults($slug), 'is_visible' => true],
            );
        }
    }
}
