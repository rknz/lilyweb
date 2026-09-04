<?php

declare(strict_types=1);

namespace Lilyweb\App\Models;

use Lilyweb\Core\Lang;

/**
 * Project Data Model with Full Bilingual Localization and Fallback Engine.
 */
class Project
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        $isBn = Lang::isBn();
        try {
            $pdo = \Lilyweb\Core\Database::connect();
            $stmt = $pdo->query("
                SELECT p.*, c.name_en AS cat_name_en, c.name_bn AS cat_name_bn
                FROM `lilyweb_projects` p
                LEFT JOIN `lilyweb_project_categories` c ON c.slug = p.room_type_key
                WHERE p.is_active = 1
                ORDER BY p.sort_order ASC, p.id ASC
            ");
            $dbRows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if (!empty($dbRows)) {
                return array_map(function ($p) use ($isBn) {
                    $gallery = json_decode($p['gallery_json'] ?: '[]', true);
                    if (!is_array($gallery) || empty($gallery)) {
                        $gallery = $p['cover_image'] ? [$p['cover_image']] : [];
                    }

                    $features = self::decodeList($p['features_json']);
                    $highlights = self::decodeHighlights($p['highlights_json']);
                    $materials = self::decodeList($p['materials_json']);

                    $catLabel = $isBn
                        ? ($p['cat_name_bn'] ?: $p['cat_name_en'] ?: 'Living Room')
                        : ($p['cat_name_en'] ?: 'Living Room');

                    // Normalise keys so they match the frontend filter taxonomies:
                    //  - room_type_key:  living_room -> living-room
                    //  - project_type_key: turnkey-duplex -> turnkey
                    $roomTypeKey = self::normaliseKey($p['room_type_key'] ?: 'living_room');
                    $projTypeKey = self::normaliseProjectType($p['property_type_key'] ?: 'residential');

                    $title = $isBn ? ($p['title_bn'] ?: $p['title_en']) : $p['title_en'];
                    $location = $isBn ? ($p['location_bn'] ?: $p['location_en']) : $p['location_en'];
                    $summary = $isBn ? ($p['summary_bn'] ?: $p['summary_en']) : $p['summary_en'];
                    $description = $isBn ? ($p['description_bn'] ?: $p['description_en'] ?: $summary) : ($p['description_en'] ?: $summary);
                    $rooms = $isBn ? ($p['room_details_bn'] ?: $p['room_details_en']) : $p['room_details_en'];
                    $style = $isBn ? ($p['design_style_bn'] ?: $p['design_style_en']) : $p['design_style_en'];

                    return [
                        'id' => (int) $p['id'],
                        'slug' => $p['slug'],
                        'title' => $title,
                        'project_type' => self::projectTypeLabel($projTypeKey, $isBn),
                        'project_type_key' => $projTypeKey,
                        'category' => $catLabel,
                        'room_type' => $catLabel,
                        'room_type_key' => $roomTypeKey,
                        'location' => $location,
                        'year' => $p['completion_year'] ?: '2026',
                        'area' => $p['area_sqft'] ?: '',
                        'rooms' => $rooms ?: 'Custom Architecture',
                        'style' => $style ?: 'Modern Luxury',
                        'status' => $p['project_status'] ?: 'Completed',
                        'image' => $p['cover_image'],
                        'gallery' => $gallery,
                        'summary' => $summary,
                        'description' => $description,
                        'key_features' => $features,
                        'highlights' => $highlights,
                        'materials' => $materials,
                    ];
                }, $dbRows);
            }
        } catch (\Throwable $e) {
            // Fallback to static below
        }

        $rawProjects = [
            [
                'id' => 1,
                'slug' => 'modern-luxury-apartment',
                'title_en' => 'Modern Luxury Apartment',
                'title_bn' => 'মডার্ন লাক্সারি অ্যাপার্টমেন্ট',
                'project_type_en' => 'Residential Project',
                'project_type_bn' => 'আবাসিক প্রজেক্ট',
                'project_type_key' => 'residential',
                'category_en' => 'Apartment',
                'category_bn' => 'অ্যাপার্টমেন্ট',
                'room_type_en' => 'Living Room',
                'room_type_bn' => 'লিভিং রুম',
                'room_type_key' => 'living-room',
                'location_en' => 'Dhanmondi, Dhaka',
                'location_bn' => 'ধানমন্ডি, ঢাকা',
                'year_en' => '2024',
                'year_bn' => '২০২৪',
                'area_en' => '1800 sq ft',
                'area_bn' => '১৮০০ বর্গফুট',
                'rooms_en' => '3 Bed, 2 Bath',
                'rooms_bn' => '৩ বেড, ২ বাথ',
                'style_en' => 'Modern Luxury',
                'style_bn' => 'মডার্ন লাক্সারি',
                'status_en' => 'Completed',
                'status_bn' => 'সম্পন্ন',
                'image' => '/assets/img/project-1.jpg',
                'gallery' => [
                    '/assets/img/project-1.jpg',
                    '/assets/img/hero-living-room.jpg',
                    '/assets/img/about-1.jpg',
                    '/assets/img/project-5.jpg',
                    '/assets/img/hero-slide-4.jpg',
                    '/assets/img/about-2.jpg',
                    '/assets/img/project-2.jpg',
                    '/assets/img/project-6.jpg',
                ],
                'summary_en' => 'A perfect blend of modern style and luxury comfort with warm tones, premium materials and elegant design.',
                'summary_bn' => 'আধুনিক নান্দনিকতা ও বিলাসবহুল আরামের নিখুঁত সমন্বয়, যাতে রয়েছে প্রিমিয়াম উপাদান ও মার্জিত ডিজাইনের ছোঁয়া।',
                'description_en' => 'This modern luxury apartment is designed for a family of four who wanted a sophisticated yet comfortable living space. The design focuses on functionality, natural light, and timeless elegance. Every element, from the custom furniture to the ambient lighting, creates a warm and inviting atmosphere.',
                'description_bn' => 'ধানমন্ডির এই আধুনিক বিলাসবহুল অ্যাপার্টমেন্টটি চার সদস্যের একটি পরিবারের জন্য নকশা করা হয়েছে, যারা একই সাথে আভিজাত্য ও স্বাচ্ছন্দ্য চেয়েছেন। পর্যাপ্ত প্রাকৃতিক আলো, দৃষ্টিনন্দন ফার্নিচার ও শান্ত লাইটিংয়ের মাধ্যমে পুরো স্পেসে তৈরি হয়েছে এক অনন্য আবহ।',
                'key_features_en' => [
                    'Open plan living & dining space',
                    'Custom made furniture & concealed storage',
                    'Premium quality Italian marble & walnut materials',
                    'Warm circadian lighting & ambient mood settings',
                    'Smart space utilization & ergonomic workflow',
                    'Expansive balcony with lush indoor greenery',
                ],
                'key_features_bn' => [
                    'উন্মুক্ত লিভিং ও ডাইনিং স্পেস লেআউট',
                    'কাস্টম মেড ফার্নিচার ও গোপন স্টোরেজ',
                    'প্রিমিয়াম কোয়ালিটি ইতালিয়ান মার্বেল ও ওয়ালনাট ফিনিশ',
                    'ওয়ার্ম অ্যাম্বিয়েন্ট মুড লাইটিং সিস্টেম',
                    'স্মার্ট স্পেস ইউটিলাইজেশন ও সহজ চলাচল',
                    'সবুজ গাছপালায় সাজানো প্রশস্ত ব্যালকনি',
                ],
                'highlights_en' => [
                    ['title' => 'Timeless & Elegant Design', 'icon' => 'sparkle'],
                    ['title' => 'High Quality Finishes', 'icon' => 'gem'],
                    ['title' => 'Functional Layout', 'icon' => 'layout'],
                    ['title' => 'Client Satisfaction', 'icon' => 'award'],
                ],
                'highlights_bn' => [
                    ['title' => 'চিরন্তন ও মার্জিত ডিজাইন', 'icon' => 'sparkle'],
                    ['title' => 'উচ্চমানের নিখুঁত ফিনিশিং', 'icon' => 'gem'],
                    ['title' => 'কার্যকর ও প্রশস্ত লেআউট', 'icon' => 'layout'],
                    ['title' => '১০০% ক্লায়েন্ট সন্তুষ্টি', 'icon' => 'award'],
                ],
                'materials_en' => [
                    'Italian Statuario Marble Flooring',
                    'Natural American Walnut Wall Paneling',
                    'Blum Soft-Close Cabinetry Hardware',
                    'Custom Architectural LED Strip Lighting',
                ],
                'materials_bn' => [
                    'ইতালিয়ান স্ট্যাচুয়ারিও মার্বেল ফ্লোরিং',
                    'ন্যাচারাল আমেরিকান ওয়ালনাট প্যানেলিং',
                    'জার্মান ব্লুম সফট-ক্লোজ হার্ডওয়্যার',
                    'কাস্টম আর্কিটেকচারাল এলইডি লাইটিং',
                ],
            ],
            [
                'id' => 2,
                'slug' => 'minimalist-bedroom-design',
                'title_en' => 'Minimalist Bedroom Design',
                'title_bn' => 'মিনিমালিস্ট বেডরুম ডিজাইন',
                'project_type_en' => 'Residential Project',
                'project_type_bn' => 'আবাসিক প্রজেক্ট',
                'project_type_key' => 'residential',
                'category_en' => 'Master Bedroom',
                'category_bn' => 'মাস্টার বেডরুম',
                'room_type_en' => 'Bedroom',
                'room_type_bn' => 'বেডরুম',
                'room_type_key' => 'bedroom',
                'location_en' => 'Gulshan, Dhaka',
                'location_bn' => 'গুলশান, ঢাকা',
                'year_en' => '2024',
                'year_bn' => '২০২৪',
                'area_en' => '450 sq ft',
                'area_bn' => '৪৫০ বর্গফুট',
                'rooms_en' => '1 Master Suite',
                'rooms_bn' => '১ মাস্টার স্যুট',
                'style_en' => 'Minimalist Luxury',
                'style_bn' => 'মিনিমালিস্ট লাক্সারি',
                'status_en' => 'Completed',
                'status_bn' => 'সম্পন্ন',
                'image' => '/assets/img/project-2.jpg',
                'gallery' => [
                    '/assets/img/project-2.jpg',
                    '/assets/img/hero-slide-2.jpg',
                    '/assets/img/project-1.jpg',
                    '/assets/img/about-1.jpg',
                    '/assets/img/project-5.jpg',
                    '/assets/img/hero-living-room.jpg',
                    '/assets/img/project-6.jpg',
                    '/assets/img/about-2.jpg',
                ],
                'summary_en' => 'Serene minimalist master bedroom crafted with acoustic wall panels, warm indirect lighting, and custom storage.',
                'summary_bn' => 'অ্যাকোস্টিক কাঠের প্যানেল, শান্ত ইনডাইরেক্ট আলো এবং কাস্টম স্টোরেজে সাজানো প্রশান্তিময় মাস্টার বেডরুম।',
                'description_en' => 'Located in Gulshan, this master bedroom suite focuses on uncluttered calm, organic textures, and peaceful harmony. Featuring custom fluted wood headboard architecture, hidden vanity storage, motorized linen drapery, and warm circadian lighting systems tailored for supreme relaxation.',
                'description_bn' => 'গুলশানের এই মাস্টার বেডরুমটি মানসিক প্রশান্তি ও বিশ্রামের জন্য নিখুঁতভাবে তৈরি। ফ্লুটেড কাঠের হেডবোর্ড, হিডেন ভ্যানিটি এবং অটোমেটিক ড্র্যাপারির ব্যবহারে পুরো কক্ষে তৈরি হয়েছে প্রাকৃতিক স্নিগ্ধতা।',
                'key_features_en' => [
                    'Custom acoustic fluted headboard wall',
                    'Concealed walk-in wardrobe with tinted glass',
                    'Motorized smart blackout drapery',
                    'Warm indirect architectural cove lighting',
                    'Floating nightstands with integrated charging',
                    'Engineered oak hardwood flooring',
                ],
                'key_features_bn' => [
                    'কাস্টম অ্যাকোস্টিক ফ্লুটেড হেডবোর্ড ওয়াল',
                    'টিন্টেড গ্লাসের আধুনিক ওয়াক-ইন ওয়ারড্রোব',
                    'মোটরাইজড স্মার্ট ব্ল্যাকআউট পর্দা',
                    'ইনডাইরেক্ট কোভ লাইটিং সিস্টেম',
                    'ফ্লোটিং সাইড টেবিল ও ওয়্যারলেস চার্জিং',
                    'ইঞ্জিনিয়ার্ড ওক কাঠের প্রিমিয়াম ফ্লোর',
                ],
                'highlights_en' => [
                    ['title' => 'Acoustic Soundproofing', 'icon' => 'sparkle'],
                    ['title' => 'Bespoke Woodwork', 'icon' => 'gem'],
                    ['title' => 'Smart Circadian Lighting', 'icon' => 'layout'],
                    ['title' => '100% Quality Execution', 'icon' => 'award'],
                ],
                'highlights_bn' => [
                    ['title' => 'শব্দহীন অ্যাকোস্টিক পরিবেশ', 'icon' => 'sparkle'],
                    ['title' => 'হস্তনির্মিত কাঠের কারুকাজ', 'icon' => 'gem'],
                    ['title' => 'স্মার্ট ও আরামদায়ক আলো', 'icon' => 'layout'],
                    ['title' => '১০০% কোয়ালিটি নিশ্চয়তা', 'icon' => 'award'],
                ],
                'materials_en' => [
                    'Engineered Light Oak Wood',
                    'Textured Acoustic Fabric Panels',
                    'Tinted Tempered Glass Partitions',
                    'Dimmable 2700K Warm LED Systems',
                ],
                'materials_bn' => [
                    'ইঞ্জিনিয়ার্ড লাইট ওক উড',
                    'টেক্সচার্ড অ্যাকোস্টিক ফেব্রিক প্যানেল',
                    'টিন্টেড টেম্পার্ড গ্লাস পার্টিশন',
                    'ডিমেবল ওয়ার্ম এলইডি সিস্টেম',
                ],
            ],
            [
                'id' => 3,
                'slug' => 'contemporary-kitchen',
                'title_en' => 'Contemporary Kitchen',
                'title_bn' => 'কনটেম্পোরারি কিচেন',
                'project_type_en' => 'Residential Project',
                'project_type_bn' => 'আবাসিক প্রজেক্ট',
                'project_type_key' => 'residential',
                'category_en' => 'Modular Kitchen',
                'category_bn' => 'মডিউলার কিচেন',
                'room_type_en' => 'Kitchen',
                'room_type_bn' => 'কিচেন',
                'room_type_key' => 'kitchen',
                'location_en' => 'Banani, Dhaka',
                'location_bn' => 'বনানী, ঢাকা',
                'year_en' => '2024',
                'year_bn' => '২০২৪',
                'area_en' => '320 sq ft',
                'area_bn' => '৩২০ বর্গফুট',
                'rooms_en' => 'Kitchen & Pantry',
                'rooms_bn' => 'কিচেন ও প্যান্ট্রি',
                'style_en' => 'Contemporary',
                'style_bn' => 'কনটেম্পোরারি',
                'status_en' => 'Completed',
                'status_bn' => 'সম্পন্ন',
                'image' => '/assets/img/project-3.jpg',
                'gallery' => [
                    '/assets/img/project-3.jpg',
                    '/assets/img/hero-slide-3.jpg',
                    '/assets/img/about-1.jpg',
                    '/assets/img/project-1.jpg',
                    '/assets/img/project-4.jpg',
                    '/assets/img/hero-living-room.jpg',
                    '/assets/img/project-5.jpg',
                    '/assets/img/project-2.jpg',
                ],
                'summary_en' => 'Sleek modular kitchen with seamless handleless cabinetry, quartz breakfast island, and smart organizers.',
                'summary_bn' => 'হ্যান্ডেললেস কেবিনেট্রি, কোয়ার্টজ ব্রেকফাস্ট আইল্যান্ড এবং স্মার্ট অর্গানাইজারে সজ্জিত আধুনিক মডিউলার কিচেন।',
                'description_en' => 'A high-efficiency contemporary culinary space in Banani. Engineered with Blum soft-close German hardware, scratch-resistant matte acrylic shutters, stain-proof composite quartz countertops, and built-in modern appliances designed for both everyday gourmet cooking and social gatherings.',
                'description_bn' => 'বনানীর এই মডার্ন কিচেনে ব্যবহার করা হয়েছে জার্মান ব্লুম ফিটিংস, স্ক্র্যাচ-প্রুফ ম্যাট এক্রিলিক এবং দাগ-প্রতিরোধী কোয়ার্টজ টপ, যা রান্নার অভিজ্ঞতাকে করে তোলে সহজ ও আনন্দদায়ক।',
                'key_features_en' => [
                    'Quartz waterfall breakfast island',
                    'Handleless matte anti-fingerprint acrylic shutters',
                    'German Blum tandembox pullout organizers',
                    'Under-cabinet task & mood illumination',
                    'Concealed ducting for heavy-duty chimney',
                    'Built-in microwave and oven tower',
                ],
                'key_features_bn' => [
                    'কোয়ার্টজ ওয়াটারফল ব্রেকফাস্ট আইল্যান্ড',
                    'হ্যান্ডেললেস ম্যাট অ্যান্টি-ফিঙ্গারপ্রিন্ট শাটার',
                    'জার্মান ব্লুম ট্যান্ডেমবক্স ড্রয়ার অর্গানাইজার',
                    'কেবিনেট আন্ডার-লাইট ও টাস্ক ইলুমিনেশন',
                    'হেভি-ডিউটি চিমনি উইথ হিডেন ডাক্টিং',
                    'বিল্ট-ইন ওভেন ও মাইক্রোওয়েভ স্পেস',
                ],
                'highlights_en' => [
                    ['title' => 'Ergonomic Kitchen Triangle', 'icon' => 'sparkle'],
                    ['title' => 'Scratch-Proof Surfaces', 'icon' => 'gem'],
                    ['title' => 'Maximized Pantry Storage', 'icon' => 'layout'],
                    ['title' => '10-Year Hardware Warranty', 'icon' => 'award'],
                ],
                'highlights_bn' => [
                    ['title' => 'এরগোনোমিক কিচেন ওয়ার্ক ট্রায়াঙ্গেল', 'icon' => 'sparkle'],
                    ['title' => 'স্ক্র্যাচ ও দাগ প্রতিরোধী সারফেস', 'icon' => 'gem'],
                    ['title' => 'বিশাল প্যান্ট্রি স্টোরেজ সুবিধা', 'icon' => 'layout'],
                    ['title' => '১০ বছরের হার্ডওয়্যার ওয়ারেন্টি', 'icon' => 'award'],
                ],
                'materials_en' => [
                    'Seamless Composite Quartz Slabs',
                    'German Blum Hardware & Hinges',
                    'Marine-Grade Anti-Bacterial Boards',
                    'Stainless Steel 304 Pullout Baskets',
                ],
                'materials_bn' => [
                    'সিমলেস কম্পোজিট কোয়ার্টজ স্ল্যাব',
                    'জার্মান ব্লুম হার্ডওয়্যার ও হিঞ্জ',
                    'মেরিন-গ্রেড অ্যান্টি-ব্যাকটেরিয়াল বোর্ড',
                    'স্টেইনলেস স্টিল ৩০৪ বাস্কেট',
                ],
            ],
            [
                'id' => 4,
                'slug' => 'executive-office-interior',
                'title_en' => 'Executive Office Interior',
                'title_bn' => 'এক্সিকিউটিভ অফিস ইন্টেরিয়র',
                'project_type_en' => 'Commercial Project',
                'project_type_bn' => 'বাণিজ্যিক প্রজেক্ট',
                'project_type_key' => 'commercial',
                'category_en' => 'Corporate Office',
                'category_bn' => 'কর্পোরেট অফিস',
                'room_type_en' => 'Office',
                'room_type_bn' => 'অফিস',
                'room_type_key' => 'office',
                'location_en' => 'Uttara, Dhaka',
                'location_bn' => 'উত্তরা, ঢাকা',
                'year_en' => '2023',
                'year_bn' => '২০২৩',
                'area_en' => '2400 sq ft',
                'area_bn' => '২৪০০ বর্গফুট',
                'rooms_en' => 'Director Suite & Boardroom',
                'rooms_bn' => 'ডিরেক্টর স্যুট ও বোর্ডরুম',
                'style_en' => 'Modern Executive',
                'style_bn' => 'মডার্ন এক্সিকিউটিভ',
                'status_en' => 'Completed',
                'status_bn' => 'সম্পন্ন',
                'image' => '/assets/img/project-4.jpg',
                'gallery' => [
                    '/assets/img/project-4.jpg',
                    '/assets/img/about-2.jpg',
                    '/assets/img/project-6.jpg',
                    '/assets/img/hero-slide-4.jpg',
                    '/assets/img/project-1.jpg',
                    '/assets/img/about-1.jpg',
                    '/assets/img/project-3.jpg',
                    '/assets/img/project-5.jpg',
                ],
                'summary_en' => 'Inspiring corporate executive workspace with acoustic partitions, modern meeting pods, and ergonomic workstations.',
                'summary_bn' => 'অ্যাকোস্টিক পার্টিশন, আধুনিক মিটিং পড এবং এরগোনোমিক ওয়ার্কস্টেশনে সজ্জিত গতিশীল কর্পোরেট অফিস।',
                'description_en' => 'Designed for a leading enterprise in Uttara, this workspace blends brand identity with productive workflow zoning. Incorporating glass conference suites, executive director lounges, warm oak acoustic baffles, and energy-efficient intelligent lighting systems.',
                'description_bn' => 'উত্তরার এই কর্পোরেট হেডকোয়ার্টারে টিম ওয়ার্ক ও প্রাইভেসি উভয় বিষয়কে প্রাধান্য দিয়ে কাঁচের কনফারেন্স রুম, সাউন্ডপ্রুফ পড এবং এনার্জি-এফিশিয়েন্ট লাইটিং যুক্ত করা হয়েছে।',
                'key_features_en' => [
                    'Acoustic double-glazed glass meeting rooms',
                    'Custom director desk with concealed wire channels',
                    'Ergonomic workstation pods with lumbar seating',
                    'Biophilic indoor green planter walls',
                    'High-CRI anti-glare architectural panel lighting',
                    'Executive lounge with refreshment counter',
                ],
                'key_features_bn' => [
                    'ডাবল-গ্লেজড সাউন্ডপ্রুফ মিটিং রুম',
                    'কাস্টম ডিরেক্টর ডেস্ক উইথ হিডেন কেবল চ্যানেল',
                    'এরগোনোমিক ওয়ার্কস্টেশন ও লাম্বার সাপোর্ট চেয়ার',
                    'বায়োফিলিক ইনডোর গ্রিন প্ল্যান্টার ওয়াল',
                    'অ্যান্টি-গ্লেয়ার আর্কিটেকচারাল প্যানেল লাইটিং',
                    'এক্সিকিউটিভ লাউঞ্জ ও রিফ্রেশমেন্ট বার',
                ],
                'highlights_en' => [
                    ['title' => 'Productive Workspace Zoning', 'icon' => 'sparkle'],
                    ['title' => 'Acoustic Sound Control', 'icon' => 'gem'],
                    ['title' => 'Biophilic Natural Elements', 'icon' => 'layout'],
                    ['title' => 'On-Time Project Handover', 'icon' => 'award'],
                ],
                'highlights_bn' => [
                    ['title' => 'উৎপাদনশীল ওয়ার্কস্পেস জোনিং', 'icon' => 'sparkle'],
                    ['title' => 'অ্যাকোস্টিক সাউন্ড কন্ট্রোল', 'icon' => 'gem'],
                    ['title' => 'প্রাকৃতিক গ্রিনারি ও ফ্রেশনেস', 'icon' => 'layout'],
                    ['title' => 'নির্দিষ্ট সময়ে প্রজেক্ট হ্যান্ডওভার', 'icon' => 'award'],
                ],
                'materials_en' => [
                    'Double-Glazed Aluminum Glass Systems',
                    'Natural Oak Veneer Desk Architecture',
                    'Modular Commercial Carpet Tiles',
                    'Acoustic Felt Ceiling Baffles',
                ],
                'materials_bn' => [
                    'ডাবল-গ্লেজড অ্যালুমিনিয়াম গ্লাস ফ্রেম',
                    'ন্যাচারাল ওক ভিনিয়ার ডেস্ক আর্কিটেকচার',
                    'মডিউলার কমার্শিয়াল কার্পেট টাইলস',
                    'অ্যাকোস্টিক ফেল্ট সিলিং ব্যাফেলস',
                ],
            ],
            [
                'id' => 5,
                'slug' => 'elegant-living-room',
                'title_en' => 'Elegant Living Room',
                'title_bn' => 'এলিগ্যান্ট লিভিং রুম',
                'project_type_en' => 'Residential Project',
                'project_type_bn' => 'আবাসিক প্রজেক্ট',
                'project_type_key' => 'residential',
                'category_en' => 'Living Room',
                'category_bn' => 'লিভিং রুম',
                'room_type_en' => 'Living Room',
                'room_type_bn' => 'লিভিং রুম',
                'room_type_key' => 'living-room',
                'location_en' => 'Mirpur, Dhaka',
                'location_bn' => 'মিরপুর, ঢাকা',
                'year_en' => '2023',
                'year_bn' => '২০২৩',
                'area_en' => '650 sq ft',
                'area_bn' => '৬৫০ বর্গফুট',
                'rooms_en' => 'Living Lounge',
                'rooms_bn' => 'লিভিং লাউঞ্জ',
                'style_en' => 'Contemporary Warm',
                'style_bn' => 'কনটেম্পোরারি ওয়ার্ম',
                'status_en' => 'Completed',
                'status_bn' => 'সম্পন্ন',
                'image' => '/assets/img/project-5.jpg',
                'gallery' => [
                    '/assets/img/project-5.jpg',
                    '/assets/img/hero-living-room.jpg',
                    '/assets/img/project-1.jpg',
                    '/assets/img/about-1.jpg',
                    '/assets/img/project-2.jpg',
                    '/assets/img/hero-slide-4.jpg',
                    '/assets/img/project-6.jpg',
                    '/assets/img/about-2.jpg',
                ],
                'summary_en' => 'Refined family living lounge with custom media console, decorative fluted accents, and plush seating.',
                'summary_bn' => 'কাস্টম মিডিয়া কনসোল, কাঠের ফ্লুটেড প্যানেলিং এবং আরামদায়ক সোফায় সাজানো নান্দনিক ফ্যামিলি লিভিং লাউঞ্জ।',
                'description_en' => 'An inviting and warm living room transformation in Mirpur. Custom-built entertainment unit with concealed cable management, ambient backlighting, warm neutral palette, and bespoke luxury seating tailored for welcoming guests and cozy family evenings.',
                'description_bn' => 'মিরপুরের এই লিভিং লাউঞ্জটিতে ওয়ার্ম ব্যাকলাইটিং, টেক্সচার্ড কালার এবং বিলাসবহুল এল-শেপ সোফার সমন্বয়ে তৈরি হয়েছে এক পরম উষ্ণ ও অতিথিপরায়ণ পরিবেশ।',
                'key_features_en' => [
                    'Integrated TV wall unit with fluted paneling',
                    'Custom upholstered L-shaped sectional sofa',
                    'Warm layered lighting with dimmable controls',
                    'Display showcase for art & curios with warm glass',
                    'Floating marble coffee table ensemble',
                    'Designer accent wall with textured metallic paint',
                ],
                'key_features_bn' => [
                    'ফ্লুটেড প্যানেলিং সহ টিভি ওয়াল ইউনিট',
                    'কাস্টম এল-আকৃতির সেকশনাল লাক্সারি সোফা',
                    'ডিমেবল ওয়ার্ম লেয়ার্ড লাইটিং সিস্টেম',
                    'শো-পিস ও আর্ট ডিসপ্লে ব্রোঞ্জ গ্লাস ইউনিট',
                    'ফ্লোটিং মার্বেল সেন্টার টেবিল সেট',
                    'টেক্সচার্ড মেটালিক এক্সেন্ট ওয়াল পেইন্ট',
                ],
                'highlights_en' => [
                    ['title' => 'Custom Upholstery & Furniture', 'icon' => 'sparkle'],
                    ['title' => 'Concealed Cable Routing', 'icon' => 'gem'],
                    ['title' => 'Warm Ambient Lighting', 'icon' => 'layout'],
                    ['title' => '100% Client Satisfaction', 'icon' => 'award'],
                ],
                'highlights_bn' => [
                    ['title' => 'হস্তনির্মিত লাক্সারি সোফা ও ফার্নিচার', 'icon' => 'sparkle'],
                    ['title' => 'হিডেন ওয়্যারিং ও ক্যাবল ম্যানেজমেন্ট', 'icon' => 'gem'],
                    ['title' => 'মুড এনহ্যান্সিং ওয়ার্ম লাইটিং', 'icon' => 'layout'],
                    ['title' => '১০০% ক্লায়েন্ট সন্তুষ্টি', 'icon' => 'award'],
                ],
                'materials_en' => [
                    'High-Density Moisture-Proof MDF',
                    'Imported Textured Upholstery Fabric',
                    'Custom Fluted Wood Panels',
                    'Tempered Bronze Glass Doors',
                ],
                'materials_bn' => [
                    'ময়েশ্চার-প্রুফ হাই-ডেনসিটি বোর্ড',
                    'আমদানিকৃত টেক্সচার্ড সোফা ফেব্রিক',
                    'কাস্টম ফ্লুটেড কাঠের প্যানেল',
                    'টেম্পার্ড ব্রোঞ্জ গ্লাস পাল্লা',
                ],
            ],
            [
                'id' => 6,
                'slug' => 'luxury-duplex-interior',
                'title_en' => 'Luxury Duplex Interior',
                'title_bn' => 'লাক্সারি ডুপ্লেক্স ইন্টেরিয়র',
                'project_type_en' => 'Turnkey Duplex',
                'project_type_bn' => 'টার্নকি ডুপ্লেক্স',
                'project_type_key' => 'turnkey',
                'category_en' => 'Duplex Residence',
                'category_bn' => 'ডুপ্লেক্স রেসিডেন্স',
                'room_type_en' => 'Living Room',
                'room_type_bn' => 'লিভিং রুম',
                'room_type_key' => 'living-room',
                'location_en' => 'Bashundhara, Dhaka',
                'location_bn' => 'বসুন্ধরা, ঢাকা',
                'year_en' => '2024',
                'year_bn' => '২০২৪',
                'area_en' => '3800 sq ft',
                'area_bn' => '৩৮০০ বর্গফুট',
                'rooms_en' => '5 Bed, 5 Bath, Double Height',
                'rooms_bn' => '৫ বেড, ৫ বাথ, ডাবল হাইট',
                'style_en' => 'Grand Luxury',
                'style_bn' => 'গ্র্যান্ড লাক্সারি',
                'status_en' => 'Completed',
                'status_bn' => 'সম্পন্ন',
                'image' => '/assets/img/project-6.jpg',
                'gallery' => [
                    '/assets/img/project-6.jpg',
                    '/assets/img/hero-slide-4.jpg',
                    '/assets/img/about-1.jpg',
                    '/assets/img/project-1.jpg',
                    '/assets/img/hero-living-room.jpg',
                    '/assets/img/project-2.jpg',
                    '/assets/img/project-4.jpg',
                    '/assets/img/project-5.jpg',
                ],
                'summary_en' => 'Double-height grand duplex living room with architectural glass railings, chandelier, and bespoke finishes.',
                'summary_bn' => 'ডাবল-হাইট গ্র্যান্ড সিলিং, ক্রিস্টাল ঝাড়বাতি এবং আর্কিটেকচারাল গ্লাস রেলিংয়ে সাজানো রাজকীয় ডুপ্লেক্স ইন্টেরিয়র।',
                'description_en' => 'A statement duplex interior in Bashundhara R/A. Features a magnificent double-height living room with grand vertical wooden louvers, custom crystal chandelier focal point, architectural floating staircase with tempered glass railings, and luxury guest lounges.',
                'description_bn' => 'বসুন্ধরার এই রাজকীয় ডুপ্লেক্সে ডাবল-হাইট সিলিংয়ে বিশাল ঝাড়বাতি, ভাসমান সিঁড়ি এবং মার্বেল মেঝের নিখুঁত আর্কিটেকচার স্থানটিকে দিয়েছে অনন্য উচ্চতা।',
                'key_features_en' => [
                    'Double-height ceiling with statement chandelier',
                    'Cantilevered floating staircase with LED under-glow',
                    'Frameless 12mm tempered glass safety balustrades',
                    'Automated centralized smart lighting system',
                    'Private upper mezzanine library and family lounge',
                    'Floor-to-ceiling panoramic glass windows',
                ],
                'key_features_bn' => [
                    'ডাবল-হাইট সিলিং উইথ গ্র্যান্ড ক্রিস্টাল ঝাড়বাতি',
                    'ভাসমান ফ্লোটিং সিঁড়ি উইথ এলইডি আন্ডার-গ্লো',
                    'ফ্রেমলেস ১২ মিমি সেফটি টেম্পার্ড গ্লাস রেলিং',
                    'সেন্ট্রালাইজড স্মার্ট অটোমেটিক লাইটিং',
                    'মেজানাইন ফ্লোর লাইব্রেরি ও ফ্যামিলি লাউঞ্জ',
                    'ফ্লোর-টু-সিলিং প্যানোরামিক গ্লাস উইন্ডো',
                ],
                'highlights_en' => [
                    ['title' => 'Grand Architectural Scale', 'icon' => 'sparkle'],
                    ['title' => 'Custom Crystal Chandelier', 'icon' => 'gem'],
                    ['title' => 'Smart Home Automation', 'icon' => 'layout'],
                    ['title' => 'Full Turnkey Execution', 'icon' => 'award'],
                ],
                'highlights_bn' => [
                    ['title' => 'বিশাল আর্কিটেকচারাল স্কেল ও আভিজাত্য', 'icon' => 'sparkle'],
                    ['title' => 'কাস্টম ক্রিস্টাল স্টেটমেন্ট ঝাড়বাতি', 'icon' => 'gem'],
                    ['title' => 'সেন্ট্রাল স্মার্ট হোম অটোমেশন', 'icon' => 'layout'],
                    ['title' => 'সম্পূর্ণ টার্নকি সফল হ্যান্ডওভার', 'icon' => 'award'],
                ],
                'materials_en' => [
                    'Italian Botticino Marble',
                    'Tempered Laminated Safety Glass',
                    'Teak Wood Architectural Louvers',
                    'Custom Brass Metal Inlays',
                ],
                'materials_bn' => [
                    'ইতালিয়ান বট্টিচিনো মার্বেল ফ্লোরিং',
                    'লেমিনোটেড সেফটি টেম্পার্ড গ্লাস',
                    'বার্মা সেগুন কাঠের লুভার্স',
                    'কাস্টম ব্রাস মেটাল ইনলেস',
                ],
            ],
            [
                'id' => 7,
                'slug' => 'commercial-tech-hub',
                'title_en' => 'Fintech Corporate Headquarters',
                'title_bn' => 'ফিনটেক কর্পোরেট হেডকোয়ার্টার',
                'project_type_en' => 'Commercial Project',
                'project_type_bn' => 'বাণিজ্যিক প্রজেক্ট',
                'project_type_key' => 'commercial',
                'category_en' => 'Commercial Office',
                'category_bn' => 'বাণিজ্যিক অফিস',
                'room_type_en' => 'Office',
                'room_type_bn' => 'অফিস',
                'room_type_key' => 'office',
                'location_en' => 'Gulshan 2, Dhaka',
                'location_bn' => 'গুলশান ২, ঢাকা',
                'year_en' => '2024',
                'year_bn' => '২০২৪',
                'area_en' => '5400 sq ft',
                'area_bn' => '৫৪০০ বর্গফুট',
                'rooms_en' => 'Executive Floors & Trading Floor',
                'rooms_bn' => 'এক্সিকিউটিভ ফ্লোর ও ট্রেডিং রুম',
                'style_en' => 'Contemporary Commercial',
                'style_bn' => 'কনটেম্পোরারি কমার্শিয়াল',
                'status_en' => 'Completed',
                'status_bn' => 'সম্পন্ন',
                'image' => '/assets/img/project-4.jpg',
                'gallery' => [
                    '/assets/img/project-4.jpg',
                    '/assets/img/about-2.jpg',
                    '/assets/img/hero-slide-4.jpg',
                ],
                'summary_en' => 'State-of-the-art financial technology headquarters with modern boardroom, townhall, and collaborative zones.',
                'summary_bn' => 'অত্যাধুনিক বোর্ডরুম, টাউনহল এবং কোলাবোরেটিভ জোন সমৃদ্ধ ফিনটেক কর্পোরেট অফিস।',
                'description_en' => 'A cutting-edge corporate commercial transformation in Gulshan 2. Featuring acoustic conference facilities, executive suites, ergonomic workstations, and high-speed intelligent facility infrastructure.',
                'description_bn' => 'গুলশান ২-এর এই ফিনটেক অফিসে স্মার্ট কনফারেন্স সুবিধা, এক্সিকিউটিভ কেবিন এবং এরগোনোমিক স্পেসের মাধ্যমে আধুনিক কাজের পরিবেশ গড়ে তোলা হয়েছে।',
                'key_features_en' => [
                    'Boardroom with integrated smart conference AV',
                    'Townhall amphitheater with acoustic walling',
                    'Executive lounge with panoramic skyline views',
                    'Biophilic wellness zones for employee focus',
                ],
                'key_features_bn' => [
                    'স্মার্ট অডিও-ভিডিও কনফারেন্স বোর্ডরুম',
                    'সাউন্ডপ্রুফ অ্যাকোস্টিক টাউনহল অ্যাম্ফিথিয়েটার',
                    'প্যানোরামিক সিটি-ভিউ এক্সিকিউটিভ লাউঞ্জ',
                    'কর্মীদের সুস্থতায় বায়োফিলিক ওয়েলনেস জোন',
                ],
                'highlights_en' => [
                    ['title' => 'Enterprise Grade Infrastructure', 'icon' => 'sparkle'],
                    ['title' => 'Smart Facility Integration', 'icon' => 'gem'],
                    ['title' => 'Acoustic Compliance', 'icon' => 'layout'],
                    ['title' => 'Turnkey Commercial Handover', 'icon' => 'award'],
                ],
                'highlights_bn' => [
                    ['title' => 'এন্টারপ্রাইজ গ্রেড আধুনিক অবকাঠামো', 'icon' => 'sparkle'],
                    ['title' => 'স্মার্ট ফ্যাসিলিটি অটোমেশন', 'icon' => 'gem'],
                    ['title' => 'আন্তর্জাতিক অ্যাকোস্টিক মানদণ্ড', 'icon' => 'layout'],
                    ['title' => 'সম্পূর্ণ কমার্শিয়াল সল্যুশন', 'icon' => 'award'],
                ],
                'materials_en' => [
                    'Sound-Dampening Acoustic Wall Fabric',
                    'Commercial Grade Carpet Tiles',
                    'Anodized Architectural Aluminum Profiles',
                ],
                'materials_bn' => [
                    'সাউন্ড-ড্যাম্পেনিং অ্যাকোস্টিক ফেব্রিক',
                    'কমার্শিয়াল হেভি-ডিউটি কার্পেট টাইলস',
                    'আর্কিটেকচারাল অ্যানোডাইজড অ্যালুমিনিয়াম',
                ],
            ],
            [
                'id' => 8,
                'slug' => 'heritage-penthouse-renovation',
                'title_en' => 'Heritage Penthouse Renovation',
                'title_bn' => 'হেরিটেজ পেন্টহাউস রিনোভেশন',
                'project_type_en' => 'Renovation Project',
                'project_type_bn' => 'রিনোভেশন প্রজেক্ট',
                'project_type_key' => 'renovation',
                'category_en' => 'Luxury Penthouse',
                'category_bn' => 'লাক্সারি পেন্টহাউস',
                'room_type_en' => 'Living Room',
                'room_type_bn' => 'লিভিং রুম',
                'room_type_key' => 'living-room',
                'location_en' => 'Baridhara DOHS, Dhaka',
                'location_bn' => 'বারিধারা ডিওএইচএস, ঢাকা',
                'year_en' => '2024',
                'year_bn' => '২০২৪',
                'area_en' => '3200 sq ft',
                'area_bn' => '৩২০০ বর্গফুট',
                'rooms_en' => '4 Bed, 4 Bath Penthouse',
                'rooms_bn' => '৪ বেড, ৪ বাথ পেন্টহাউস',
                'style_en' => 'Modern Classical',
                'style_bn' => 'মডার্ন ক্লাসিক্যাল',
                'status_en' => 'Completed',
                'status_bn' => 'সম্পন্ন',
                'image' => '/assets/img/hero-slide-4.jpg',
                'gallery' => [
                    '/assets/img/hero-slide-4.jpg',
                    '/assets/img/project-6.jpg',
                    '/assets/img/project-1.jpg',
                ],
                'summary_en' => 'Full structural renovation of a 3200 sq ft penthouse into a modern classical sanctuary.',
                'summary_bn' => '৩২০০০ বর্গফুটের একটি পুরাতন পেন্টহাউসকে আধুনিক ও ক্লাসিক্যাল নান্দনিকতায় রূপান্তরের সম্পূর্ণ রিনোভেশন।',
                'description_en' => 'Complete turnkey renovation in Baridhara DOHS. Re-engineered layout, custom gypsum ceiling moldings, smart electrical rewiring, imported Spanish tiles, and luxury bespoke woodwork.',
                'description_bn' => 'বারিধারা ডিওএইচএস-এর এই পেন্টহাউসটিতে সম্পূর্ণ লেআউট পুনর্গঠন, জিপসাম সিলিং মোল্ডিং ও স্প্যানিশ টাইলসের ব্যবহারে ঐতিহ্য ও আধুনিকতার মেলবন্ধন ঘটানো হয়েছে।',
                'key_features_en' => [
                    'Complete spatial layout restructuring',
                    'Custom classical wall mouldings & cornice',
                    'Modernized thermal and acoustic window glazing',
                    'Bespoke kitchen and luxury bathroom overhaul',
                ],
                'key_features_bn' => [
                    'সম্পূর্ণ স্থান পুনর্বিন্যাস ও আর্কিটেকচারাল রিডিজাইন',
                    'হস্তনির্মিত ক্লাসিক্যাল ওয়াল মোল্ডিং ও কর্নিশ',
                    'অ্যাকোস্টিক ও থার্মাল আধুনিক উইন্ডো গ্লেজিং',
                    'লাক্সারি কিচেন ও বাথরুমের পূর্ণ সংস্কার',
                ],
                'highlights_en' => [
                    ['title' => 'Full Structural Overhaul', 'icon' => 'sparkle'],
                    ['title' => 'Classical Artisanal Finishes', 'icon' => 'gem'],
                    ['title' => 'Energy Efficient Renovation', 'icon' => 'layout'],
                    ['title' => 'Zero-Defect Handover', 'icon' => 'award'],
                ],
                'highlights_bn' => [
                    ['title' => 'সম্পূর্ণ স্ট্রাকচারাল ওভারহোল', 'icon' => 'sparkle'],
                    ['title' => 'ক্লাসিক্যাল কারুকার্যময় ফিনিশিং', 'icon' => 'gem'],
                    ['title' => 'বিদ্যুৎ-সাশ্রয়ী আধুনিক রিনোভেশন', 'icon' => 'layout'],
                    ['title' => 'ত্রুটিহীন সফল প্রজেক্ট সমর্পণ', 'icon' => 'award'],
                ],
                'materials_en' => [
                    'Imported Spanish Porcelain Tiles',
                    'Solid Burma Teak Door Architecture',
                    'High-Density Gypsum Decorative Moldings',
                ],
                'materials_bn' => [
                    'আমদানিকৃত স্প্যানিশ পোরসেলিন টাইলস',
                    'বার্মা সেগুন কাঠের রাজকীয় দরজা',
                    'হাই-ডেনসিটি জিপসাম ডেকোরেটিভ মোল্ডিংস',
                ],
            ],
        ];

        // Hydrate localized properties dynamically based on current locale
        $isBn = Lang::isBn();
        return array_map(function ($p) use ($isBn) {
            return [
                'id' => $p['id'],
                'slug' => $p['slug'],
                'title' => $isBn ? ($p['title_bn'] ?? $p['title_en']) : $p['title_en'],
                'project_type' => $isBn ? ($p['project_type_bn'] ?? $p['project_type_en']) : $p['project_type_en'],
                'project_type_key' => $p['project_type_key'],
                'category' => $isBn ? ($p['category_bn'] ?? $p['category_en']) : $p['category_en'],
                'room_type' => $isBn ? ($p['room_type_bn'] ?? $p['room_type_en']) : $p['room_type_en'],
                'room_type_key' => $p['room_type_key'],
                'location' => $isBn ? ($p['location_bn'] ?? $p['location_en']) : $p['location_en'],
                'year' => $isBn ? ($p['year_bn'] ?? $p['year_en']) : $p['year_en'],
                'area' => $isBn ? ($p['area_bn'] ?? $p['area_en']) : $p['area_en'],
                'rooms' => $isBn ? ($p['rooms_bn'] ?? $p['rooms_en']) : $p['rooms_en'],
                'style' => $isBn ? ($p['style_bn'] ?? $p['style_en']) : $p['style_en'],
                'status' => $isBn ? ($p['status_bn'] ?? $p['status_en']) : $p['status_en'],
                'image' => $p['image'],
                'gallery' => $p['gallery'],
                'summary' => $isBn ? ($p['summary_bn'] ?? $p['summary_en']) : $p['summary_en'],
                'description' => $isBn ? ($p['description_bn'] ?? $p['description_en']) : $p['description_en'],
                'key_features' => $isBn ? ($p['key_features_bn'] ?? $p['key_features_en']) : $p['key_features_en'],
                'highlights' => $isBn ? ($p['highlights_bn'] ?? $p['highlights_en']) : $p['highlights_en'],
                'materials' => $isBn ? ($p['materials_bn'] ?? $p['materials_en']) : $p['materials_en'],
            ];
        }, $rawProjects);
    }

    /** Decode a JSON list of plain strings; returns [] on failure. */
    private static function decodeList(?string $json): array
    {
        if (!$json) {
            return [];
        }
        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            return [];
        }
        return array_values(array_filter($decoded, 'is_string'));
    }

    /** Decode a JSON list of highlight objects into [['title'=>...]] entries. */
    private static function decodeHighlights(?string $json): array
    {
        if (!$json) {
            return [];
        }
        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            return [];
        }
        $out = [];
        foreach ($decoded as $item) {
            if (!is_array($item)) {
                continue;
            }
            $out[] = [
                'title' => (string) ($item['title'] ?? ''),
                'icon' => (string) ($item['icon'] ?? 'sparkle'),
            ];
        }
        return $out;
    }

    /** Normalise a taxonomy key (underscores -> hyphens) for filter matching. */
    private static function normaliseKey(string $key): string
    {
        return strtolower(trim(str_replace('_', '-', $key), '-')) ?: 'living-room';
    }

    /** Map the DB property_type_key onto the frontend filter taxonomy. */
    private static function normaliseProjectType(string $key): string
    {
        $key = strtolower(trim($key));
        if ($key === 'turnkey-duplex' || $key === 'turnkey_duplex') {
            return 'turnkey';
        }
        if ($key === '') {
            return 'residential';
        }
        return $key;
    }

    /** Human-readable "X Project" label for the property type key. */
    private static function projectTypeLabel(string $key, bool $isBn): string
    {
        $labels = [
            'residential' => ['en' => 'Residential Project', 'bn' => 'আবাসিক প্রজেক্ট'],
            'commercial' => ['en' => 'Commercial Project', 'bn' => 'বাণিজ্যিক প্রজেক্ট'],
            'turnkey' => ['en' => 'Turnkey Duplex Project', 'bn' => 'টার্নকি ডুপ্লেক্স প্রজেক্ট'],
            'renovation' => ['en' => 'Renovation Project', 'bn' => 'রিনোভেশন প্রজেক্ট'],
        ];
        $label = $labels[$key] ?? ['en' => 'Project', 'bn' => 'প্রজেক্ট'];
        return $isBn ? $label['bn'] : $label['en'];
    }

    public static function findBySlug(string $slug): ?array
    {
        foreach (self::all() as $project) {
            if ($project['slug'] === $slug) {
                return $project;
            }
        }
        return null;
    }

    /**
     * Room / Space Types Taxonomy (used by Homepage Portfolio filter)
     */
    public static function roomTypes(): array
    {
        $isBn = Lang::isBn();
        return [
            'all' => $isBn ? 'সবগুলো' : 'All',
            'living-room' => $isBn ? 'লিভিং রুম' : 'Living Room',
            'bedroom' => $isBn ? 'বেডরুম' : 'Bedroom',
            'kitchen' => $isBn ? 'কিচেন' : 'Kitchen',
            'office' => $isBn ? 'অফিস' : 'Office',
            'others' => $isBn ? 'অন্যান্য' : 'Others',
        ];
    }

    /**
     * Property / Project Types Taxonomy (used by /projects Directory filter)
     */
    public static function projectTypes(): array
    {
        $isBn = Lang::isBn();
        return [
            'all' => $isBn ? 'সকল প্রজেক্ট' : 'All Projects',
            'residential' => $isBn ? 'আবাসিক' : 'Residential',
            'commercial' => $isBn ? 'বাণিজ্যিক' : 'Commercial',
            'turnkey' => $isBn ? 'টার্নকি ডুপ্লেক্স' : 'Turnkey Duplex',
            'renovation' => $isBn ? 'রিনোভেশন' : 'Renovation',
        ];
    }
}
