<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSection extends Model
{
    protected $fillable = ['slug', 'content', 'is_visible'];

    protected function casts(): array
    {
        return ['content' => 'array', 'is_visible' => 'boolean'];
    }

    public static function allDefaults(): array
    {
        return [
            'site' => [
                'site_name' => 'দৃশ্যপ্রো',
                'phone' => '০১৭০০‑০০০০০০',
                'phone_link' => '01700000000',
                'copyright' => '© ২০২৬ দৃশ্যপ্রো। সর্বস্বত্ব সংরক্ষিত।',
            ],
            'social' => [
                'whatsapp_link' => 'https://wa.me/8801700000000',
                'facebook_link' => '#',
                'youtube_link' => '#',
            ],
            'seo' => [
                'meta_title' => 'দৃশ্যপ্রো | পেশাদার এইচডি ক্যামেরা',
                'meta_description' => 'পেশাদার এইচডি ক্যামেরা—আল্ট্রা এইচডি ছবি, ৪কে ভিডিও, নাইট ভিশন ও শক্তিশালী জুম। সারা বাংলাদেশে ক্যাশ অন ডেলিভারি।',
                'meta_keywords' => 'পেশাদার ক্যামেরা, এইচডি ক্যামেরা, ৪কে ক্যামেরা, বাংলাদেশে ক্যামেরা',
                'og_description' => 'মুহূর্তকে ধরে রাখুন একদম পেশাদার মানের ছবিতে। বিশেষ মূল্য ৳১৩,৯৯০।',
            ],
            'hero' => [
                'badge' => 'সীমিত সময়ের অফার',
                'title_before' => 'মুহূর্তকে ধরে রাখুন',
                'title_highlight' => 'একদম পেশাদার',
                'title_after' => 'মানের ছবিতে',
                'description' => 'ভ্রমণ, অনুষ্ঠান কিংবা কনটেন্ট—প্রতিটি ফ্রেমে পান অসাধারণ স্বচ্ছতা, জীবন্ত রং ও সিনেমাটিক গভীরতা।',
                'old_price' => 18500,
                'price' => 13990,
            ],
            'stats' => ['title' => 'ক্যামেরার পরিসংখ্যান'],
            'features' => ['kicker' => 'শক্তিশালী ফিচার', 'title' => 'আপনার সৃজনশীলতাকে দিন নতুন মাত্রা'],
            'story' => ['kicker' => 'কেন এই ক্যামেরা?', 'title' => 'শুধু ক্যামেরা নয়, এটি আপনার সৃজনশীল সঙ্গী'],
            'specification' => ['kicker' => 'সম্পূর্ণ স্পেসিফিকেশন', 'title' => 'ভেতরে শক্তিশালী, বাইরে অনবদ্য'],
            'gallery' => ['kicker' => 'ক্যামেরায় তোলা', 'title' => 'প্রতিটি ফ্রেমেই জীবন্ত গল্প'],
            'reviews' => ['kicker' => 'গ্রাহকের ভালোবাসা', 'title' => 'বাংলাদেশজুড়ে বিশ্বাসের গল্প'],
            'pricing' => ['kicker' => 'আজকের বিশেষ মূল্য', 'price' => 13990, 'old_price' => 18500],
            'order' => [
                'kicker' => 'মাত্র এক মিনিটে অর্ডার',
                'title' => 'আপনার তথ্য দিন, পণ্য বুঝে পেয়ে মূল্য দিন',
                'product_name' => 'দৃশ্যপ্রো এইচডি ক্যামেরা',
                'price' => 13990,
            ],
            'faq' => ['kicker' => 'সচরাচর জিজ্ঞাসা', 'title' => 'আপনার প্রশ্নের সহজ উত্তর'],
            'trust' => ['title' => 'বিশ্বস্ত কেনাকাটার নিশ্চয়তা'],
            'cta' => ['eyebrow' => 'স্টক সীমিত', 'title' => 'আজই অর্ডার করুন এবং বিশেষ মূল্য ছাড় উপভোগ করুন'],
        ];
    }

    public static function defaults(string $slug): array
    {
        return self::allDefaults()[$slug] ?? [];
    }

    public static function definitions(): array
    {
        $labels = [
            'site' => 'সাধারণ সেটিংস',
            'social' => 'সামাজিক যোগাযোগ',
            'seo' => 'এসইও সেটিংস',
            'hero' => 'হিরো বিভাগ',
            'stats' => 'পরিসংখ্যান',
            'features' => 'ফিচারসমূহ',
            'story' => 'কেন এই ক্যামেরা',
            'specification' => 'স্পেসিফিকেশন',
            'gallery' => 'গ্যালারি',
            'reviews' => 'গ্রাহক রিভিউ',
            'pricing' => 'মূল্য ও অফার',
            'order' => 'অর্ডার ফরম',
            'faq' => 'সচরাচর প্রশ্ন',
            'trust' => 'বিশ্বাসযোগ্যতা',
            'cta' => 'শেষ আহ্বান',
        ];

        $definitions = [];
        foreach (self::allDefaults() as $slug => $defaults) {
            $fields = [];
            foreach ($defaults as $key => $value) {
                $type = is_numeric($value)
                    ? 'number'
                    : (str_contains($key, 'description') || str_contains($key, 'text') ? 'textarea' : 'text');
                $fields[$key] = [ucwords(str_replace('_', ' ', $key)), $type];
            }
            $definitions[$slug] = ['label' => $labels[$slug], 'fields' => $fields];
        }

        return $definitions;
    }

    public static function reviewItems(array $content): array
    {
        return $content['reviews'] ?? [];
    }
}
