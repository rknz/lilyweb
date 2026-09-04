<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers\Admin;

use Lilyweb\Core\Request;
use Lilyweb\Core\Response;
use Lilyweb\Core\Auth;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;
use Lilyweb\Core\Session;
use Lilyweb\Core\View;
use PDO;
use Exception;

final class HeroController extends AdminController
{
    /**
     * List all hero slides.
     */
    public function index(array $params = []): Response
    {
        $this->requireAuth();

        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM `lilyweb_hero_slides` ORDER BY `sort_order` ASC, `id` ASC");
        $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = View::make('admin.hero.index', [
            'slides' => $slides,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Hero Slides Management',
            'pageHeading' => 'Hero Slides Management',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show edit form for a slide.
     */
    public function edit(array $params = []): Response
    {
        $this->requireAuth();

        $id = (int) ($params['id'] ?? 0);
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM `lilyweb_hero_slides` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $slide = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$slide) {
            Session::flash('error', 'Hero slide not found.');
            return Response::redirect('/admin/hero');
        }

        $content = View::make('admin.hero.form', [
            'slide' => $slide,
            'isEdit' => true,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Edit Hero Slide #' . $slide['step_number'],
            'pageHeading' => 'Edit Hero Slide #' . $slide['step_number'],
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Show create form for a new slide.
     */
    public function create(array $params = []): Response
    {
        $this->requireAuth();

        $slide = [
            'id' => 0,
            'step_number' => '06',
            'kicker_en' => 'DESIGN • CREATE • INSPIRE',
            'kicker_bn' => '',
            'title_prefix_en' => 'We Design Spaces That',
            'title_prefix_bn' => '',
            'title_highlight_en' => 'Inspire',
            'title_highlight_bn' => '',
            'title_suffix_en' => 'Life',
            'title_suffix_bn' => '',
            'subtitle_en' => '',
            'subtitle_bn' => '',
            'image_url' => '/assets/img/hero-living-room.jpg',
            'badge_room_en' => 'Living Room',
            'badge_room_bn' => '',
            'badge_location_en' => 'Dhaka',
            'badge_location_bn' => '',
            'cta_text_en' => 'Explore Projects →',
            'cta_text_bn' => '',
            'cta_url' => '#portfolio',
            'sort_order' => 10,
            'is_active' => 1,
        ];

        $content = View::make('admin.hero.form', [
            'slide' => $slide,
            'isEdit' => false,
        ])->render();

        $html = View::renderPartial('admin.layouts.admin', [
            'pageTitle' => 'Add New Hero Slide',
            'pageHeading' => 'Add New Hero Slide',
            'content' => $content,
        ]);

        return new Response($html);
    }

    /**
     * Save/update hero slide.
     */
    public function save(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        $pdo = Database::connect();

        $stepNumber = (string) Request::post('step_number', '01');
        $kickerEn = (string) Request::post('kicker_en', '');
        $kickerBn = (string) Request::post('kicker_bn', '');
        $titlePrefixEn = (string) Request::post('title_prefix_en', '');
        $titlePrefixBn = (string) Request::post('title_prefix_bn', '');
        $titleHighlightEn = (string) Request::post('title_highlight_en', '');
        $titleHighlightBn = (string) Request::post('title_highlight_bn', '');
        $titleSuffixEn = (string) Request::post('title_suffix_en', '');
        $titleSuffixBn = (string) Request::post('title_suffix_bn', '');
        $subtitleEn = (string) Request::post('subtitle_en', '');
        $subtitleBn = (string) Request::post('subtitle_bn', '');
        $imageUrl = (string) Request::post('image_url', '/assets/img/hero-living-room.jpg');
        $badgeRoomEn = (string) Request::post('badge_room_en', '');
        $badgeRoomBn = (string) Request::post('badge_room_bn', '');
        $badgeLocationEn = (string) Request::post('badge_location_en', '');
        $badgeLocationBn = (string) Request::post('badge_location_bn', '');
        $ctaTextEn = (string) Request::post('cta_text_en', 'Explore Projects →');
        $ctaTextBn = (string) Request::post('cta_text_bn', '');
        $ctaUrl = (string) Request::post('cta_url', '#portfolio');
        $sortOrder = (int) Request::post('sort_order', 0);
        $isActive = (int) (Request::post('is_active') ? 1 : 0);

        if (trim($titlePrefixEn) === '' && trim($titleHighlightEn) === '') {
            Session::flash('error', 'English title fields are required.');
            return Response::redirect($id > 0 ? "/admin/hero/edit/{$id}" : '/admin/hero/create');
        }

        try {
            if ($id > 0) {
                $stmt = $pdo->prepare("
                    UPDATE `lilyweb_hero_slides` SET
                        `step_number` = :step,
                        `kicker_en` = :ke,
                        `kicker_bn` = :kb,
                        `title_prefix_en` = :tpe,
                        `title_prefix_bn` = :tpb,
                        `title_highlight_en` = :the,
                        `title_highlight_bn` = :thb,
                        `title_suffix_en` = :tse,
                        `title_suffix_bn` = :tsb,
                        `subtitle_en` = :sube,
                        `subtitle_bn` = :subb,
                        `image_url` = :img,
                        `badge_room_en` = :bre,
                        `badge_room_bn` = :brb,
                        `badge_location_en` = :ble,
                        `badge_location_bn` = :blb,
                        `cta_text_en` = :ctae,
                        `cta_text_bn` = :ctab,
                        `cta_url` = :ctau,
                        `sort_order` = :sort,
                        `is_active` = :active
                    WHERE `id` = :id
                ");
                $stmt->execute([
                    ':step' => $stepNumber,
                    ':ke' => $kickerEn,
                    ':kb' => $kickerBn ?: null,
                    ':tpe' => $titlePrefixEn,
                    ':tpb' => $titlePrefixBn ?: null,
                    ':the' => $titleHighlightEn,
                    ':thb' => $titleHighlightBn ?: null,
                    ':tse' => $titleSuffixEn,
                    ':tsb' => $titleSuffixBn ?: null,
                    ':sube' => $subtitleEn,
                    ':subb' => $subtitleBn ?: null,
                    ':img' => $imageUrl,
                    ':bre' => $badgeRoomEn,
                    ':brb' => $badgeRoomBn ?: null,
                    ':ble' => $badgeLocationEn,
                    ':blb' => $badgeLocationBn ?: null,
                    ':ctae' => $ctaTextEn,
                    ':ctab' => $ctaTextBn ?: null,
                    ':ctau' => $ctaUrl,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                    ':id' => $id,
                ]);

                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'update', 'hero_slide', (string) $id, Request::ip());
                Session::flash('success', "Hero slide #{$stepNumber} updated successfully and live on website.");
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO `lilyweb_hero_slides`
                    (`step_number`, `kicker_en`, `kicker_bn`, `title_prefix_en`, `title_prefix_bn`, `title_highlight_en`, `title_highlight_bn`, `title_suffix_en`, `title_suffix_bn`, `subtitle_en`, `subtitle_bn`, `image_url`, `badge_room_en`, `badge_room_bn`, `badge_location_en`, `badge_location_bn`, `cta_text_en`, `cta_text_bn`, `cta_url`, `sort_order`, `is_active`)
                    VALUES (:step, :ke, :kb, :tpe, :tpb, :the, :thb, :tse, :tsb, :sube, :subb, :img, :bre, :brb, :ble, :blb, :ctae, :ctab, :ctau, :sort, :active)
                ");
                $stmt->execute([
                    ':step' => $stepNumber,
                    ':ke' => $kickerEn,
                    ':kb' => $kickerBn ?: null,
                    ':tpe' => $titlePrefixEn,
                    ':tpb' => $titlePrefixBn ?: null,
                    ':the' => $titleHighlightEn,
                    ':thb' => $titleHighlightBn ?: null,
                    ':tse' => $titleSuffixEn,
                    ':tsb' => $titleSuffixBn ?: null,
                    ':sube' => $subtitleEn,
                    ':subb' => $subtitleBn ?: null,
                    ':img' => $imageUrl,
                    ':bre' => $badgeRoomEn,
                    ':brb' => $badgeRoomBn ?: null,
                    ':ble' => $badgeLocationEn,
                    ':blb' => $badgeLocationBn ?: null,
                    ':ctae' => $ctaTextEn,
                    ':ctab' => $ctaTextBn ?: null,
                    ':ctau' => $ctaUrl,
                    ':sort' => $sortOrder,
                    ':active' => $isActive,
                ]);

                $newId = (int) $pdo->lastInsertId();
                Auth::logAudit(Auth::user()['username'] ?? 'admin', 'create', 'hero_slide', (string) $newId, Request::ip());
                Session::flash('success', "New hero slide #{$stepNumber} created and live on website.");
            }
        } catch (Exception $e) {
            Session::flash('error', 'Failed to save hero slide: ' . $e->getMessage());
            return Response::redirect($id > 0 ? "/admin/hero/edit/{$id}" : '/admin/hero/create');
        }

        return Response::redirect('/admin/hero');
    }

    /**
     * Delete a hero slide.
     */
    public function delete(array $params = []): Response
    {
        $this->requireAuth();
        Security::verifyCsrf();

        $id = (int) Request::post('id', 0);
        if ($id > 0) {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("DELETE FROM `lilyweb_hero_slides` WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
            Auth::logAudit(Auth::user()['username'] ?? 'admin', 'delete', 'hero_slide', (string) $id, Request::ip());
            Session::flash('success', 'Hero slide deleted successfully.');
        }

        return Response::redirect('/admin/hero');
    }
}
