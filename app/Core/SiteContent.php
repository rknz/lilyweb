<?php

declare(strict_types=1);

namespace Lilyweb\Core;

use PDO;

/**
 * Shared site content provider — single source of truth for the reusable
 * components rendered on every page (navigation, footer, contact/social links,
 * stats and primary content blocks).
 *
 * Centralising these values removes the duplication that previously scattered
 * the same phone/email/WhatsApp/nav/stats across header, footer, contact and
 * the controllers. Values are resolved once per request via a static cache, so
 * header + footer + contact each fetch the same data without re-reading
 * configuration or (in later phases) re-querying the database.
 *
 * Draft placeholders (CONTENT_BLUEPRINT) must be confirmed by the owner before
 * launch — they are never treated as verified business data.
 */
final class SiteContent
{
    /** @var array<string,mixed> Per-request resolved cache. */
    private static array $cache = [];

    public static function flush(): void
    {
        self::$cache = [];
    }

    public static function name(): string
    {
        return (string) self::cached('name', fn () => Config::get('app.name', 'Lily Interiors'));
    }

    public static function tagline(): string
    {
        return (string) self::cached('tagline', fn () => 'Professional Interior & Renovation Studio');
    }

    /**
     * Contact details used by the header top-strip, contact section, floating
     * actions and footer. Central so a single change propagates everywhere.
     *
     * @return array<string,array{label:string,value:string,href:string|''}>
     */
    public static function contact(): array
    {
        return self::cached('contact', static function () {
            $settings = [];
            try {
                $stmt = Database::connect()->query("SELECT `setting_key`, `setting_value` FROM `lilyweb_site_settings`");
                $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            } catch (\Throwable $e) {
                // Fail-safe: fall back to sensible defaults below.
            }

            $phone = $settings['phone_primary'] ?? '+88 01734182694';
            $email = $settings['email_primary'] ?? 'lilyinteriorsbd@gmail.com';
            $waNumber = preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '8801734182694');
            $hourEn = $settings['business_hours_en'] ?? 'Sat – Thu, 9am – 7pm';
            $address = $settings['address_en'] ?? '36 Bir Uttam C.R Dutta Road, Hatirpool, Dhaka-1205, Bangladesh';

            return [
                'phone'  => ['label' => 'Phone / WhatsApp', 'value' => $phone, 'href' => 'tel:' . preg_replace('/\s+/', '', $phone)],
                'email'  => ['label' => 'Email', 'value' => $email, 'href' => 'mailto:' . $email],
                'whatsapp' => ['label' => 'WhatsApp', 'value' => $phone, 'href' => 'https://wa.me/' . $waNumber],
                'address' => ['label' => 'Studio', 'value' => $address, 'href' => ''],
                'hours'   => ['label' => 'Working Hours', 'value' => $hourEn, 'href' => ''],
            ];
        });
    }

    /**
     * Social / chat links. Used by the contact section and floating actions.
     *
     * @return array<int,array{label:string,service:string,href:string}>
     */
    public static function socialLinks(): array
    {
        return self::cached('socialLinks', static function () {
            $contact = self::contact();
            return [
                ['label' => 'WhatsApp', 'service' => 'whatsapp', 'href' => $contact['whatsapp']['href']],
                ['label' => 'Phone', 'service' => 'phone', 'href' => $contact['phone']['href']],
                ['label' => 'Email', 'service' => 'email', 'href' => $contact['email']['href']],
            ];
        });
    }

    /**
     * Primary navigation (header).
     *
     * @return array<string,array{label:string,href:string}>
     */
    public static function nav(): array
    {
        return self::cached('nav', static fn () => [
            'home'     => ['label' => 'Home', 'href' => '/'],
            'about'    => ['label' => 'About', 'href' => '/#about'],
            'services' => ['label' => 'Services', 'href' => '/#services'],
            'projects' => ['label' => 'Projects', 'href' => '/#portfolio'],
            'contact'  => ['label' => 'Contact', 'href' => '/#contact'],
        ]);
    }

    /**
     * Footer navigation columns.
     *
     * @return array<string,array<int,array{label:string,href:string}>>
     */
    public static function footerNav(): array
    {
        return self::cached('footerNav', static fn () => [
            'Explore' => [
                ['label' => 'Home', 'href' => '/'],
                ['label' => 'About', 'href' => '/#about'],
                ['label' => 'Services', 'href' => '/services'],
                ['label' => 'Portfolio', 'href' => '/#portfolio'],
                ['label' => 'Our Process', 'href' => '/#process'],
            ],
            'Help & Support' => [
                ['label' => 'Contact Us', 'href' => '/#contact'],
                ['label' => 'View All Projects', 'href' => '/projects/'],
                ['label' => 'Privacy Policy', 'href' => '/privacy-policy'],
                ['label' => 'Terms', 'href' => '/terms'],
                ['label' => 'Get a Quote', 'href' => '/#contact'],
            ],
        ]);
    }

    /**
     * Trust / achievement statistics. Shared by the stats partial and homepage.
     *
     * @return array<int,array{value:int|float,prefix?:string,suffix?:string,label:string}>
     */
    public static function stats(): array
    {
        return self::cached('stats', static fn () => [
            ['value' => 250, 'suffix' => '+', 'label' => 'Projects Completed'],
            ['value' => 180, 'suffix' => '+', 'label' => 'Happy Clients'],
            ['value' => 8,   'suffix' => '+', 'label' => 'Years Experience'],
            ['value' => 15,  'suffix' => '+', 'label' => 'Expert Designers'],
            ['value' => 100, 'suffix' => '%', 'label' => 'Client Satisfaction'],
        ]);
    }

    /**
     * Core services. Shared by the services partial and homepage.
     *
     * @return array<int,array{title:string,icon:string,summary:string}>
     */
    public static function services(): array
    {
        return self::cached('services', static fn () => [
            ['title' => 'Interior Design', 'icon' => '&#10022;', 'summary' => 'Concept-driven interior layouts tailored to your lifestyle, from mood boards to full-space planning.'],
            ['title' => 'Renovation', 'icon' => '&#9873;', 'summary' => 'Thoughtful renovation that modernises your home while preserving its character and value.'],
            ['title' => 'Modular Kitchen', 'icon' => '&#9698;', 'summary' => 'Ergonomic modular kitchen systems built for beautiful, efficient daily living.'],
            ['title' => 'False Ceiling', 'icon' => '&#9650;', 'summary' => 'Elegant false-ceiling designs that elevate acoustics, lighting and atmosphere.'],
            ['title' => 'Office Interior', 'icon' => '&#9632;', 'summary' => 'Productive, on-brand work environments designed for teams and visitors alike.'],
            ['title' => 'Turnkey Project', 'icon' => '&#10003;', 'summary' => 'Single-point responsibility from design to handover — a truly hassle-free experience.'],
        ]);
    }

    /**
     * Portfolio preview projects. Shared by the portfolio partial and homepage.
     *
     * @return array<int,array{category:string,title:string,location:string}>
     */
    public static function projects(): array
    {
        return self::cached('projects', static fn () => [
            ['category' => 'Living Room', 'title' => 'Living Room', 'location' => 'Dhanmondi'],
            ['category' => 'Bedroom', 'title' => 'Bedroom Retreat', 'location' => 'Gulshan'],
            ['category' => 'Kitchen', 'title' => 'Modular Kitchen', 'location' => 'Gulshan'],
            ['category' => 'Office', 'title' => 'Corporate Office', 'location' => 'Mirpur'],
            ['category' => 'Living Room', 'title' => 'Family Lounge', 'location' => 'Banani'],
            ['category' => 'Bedroom', 'title' => 'Master Suite', 'location' => 'Uttara'],
            ['category' => 'Office', 'title' => 'Startup Workspace', 'location' => 'Dhanmondi'],
            ['category' => 'Others', 'title' => 'Café Interior', 'location' => 'Bashundhara'],
            ['category' => 'Living Room', 'title' => 'Penthouse Living', 'location' => 'Baridhara'],
        ]);
    }

    /**
     * Resolve a value once per request, then cache it.
     */
    private static function cached(string $key, \Closure $resolver): mixed
    {
        if (!array_key_exists($key, self::$cache)) {
            self::$cache[$key] = $resolver();
        }
        return self::$cache[$key];
    }
}
