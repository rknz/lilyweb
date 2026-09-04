<?php

/**
 * Web routes.
 *
 * Registration order matters: more specific routes should be registered first.
 * Handlers are [ControllerClass::class, 'method'] arrays or closures.
 */

use Lilyweb\Core\Router;
use Lilyweb\App\Controllers\HomeController;
use Lilyweb\App\Controllers\ProjectController;
use Lilyweb\App\Controllers\PageController;
use Lilyweb\App\Controllers\PlaceholderController;

// Homepage
Router::get('/', [HomeController::class, 'index']);

// Projects Portfolio Directory & Dynamic Project Details Page
Router::get('/projects', [ProjectController::class, 'index']);
Router::get('/projects/', [ProjectController::class, 'index']);
Router::get('/projects/{slug}', [ProjectController::class, 'show']);

// FAQ Page
Router::get('/faq', [PageController::class, 'faq']);

// Informational & linked pages
Router::get('/about', [PageController::class, 'about']);
Router::get('/contact', [PageController::class, 'contact']);
Router::get('/services', [PageController::class, 'services']);
Router::get('/privacy-policy', [PageController::class, 'privacyPolicy']);
Router::get('/privacy', [PageController::class, 'privacyPolicy']);
Router::get('/terms', [PageController::class, 'terms']);
Router::get('/terms-and-conditions', [PageController::class, 'terms']);
Router::get('/terms-conditions', [PageController::class, 'terms']);

// Foundation status test page
Router::get('/foundation', [PlaceholderController::class, 'index']);

// ============================================================
// Owner Administration & CMS Routes
// ============================================================
use Lilyweb\App\Controllers\Admin\AuthController;
use Lilyweb\App\Controllers\Admin\DashboardController;

Router::get('/admin/login', [AuthController::class, 'showLogin']);
Router::post('/admin/login', [AuthController::class, 'login']);
Router::post('/admin/logout', [AuthController::class, 'logout']);
Router::get('/admin/logout', [AuthController::class, 'logout']);

Router::get('/admin', [DashboardController::class, 'index']);
Router::get('/admin/dashboard', [DashboardController::class, 'index']);

// Homepage Master Section Builder
use Lilyweb\App\Controllers\Admin\HomepageController;
Router::get('/admin/homepage', [HomepageController::class, 'index']);

// Hero Slides Module
use Lilyweb\App\Controllers\Admin\HeroController;
Router::get('/admin/hero', [HeroController::class, 'index']);
Router::get('/admin/hero/create', [HeroController::class, 'create']);
Router::get('/admin/hero/edit/{id}', [HeroController::class, 'edit']);
Router::post('/admin/hero/save', [HeroController::class, 'save']);
Router::post('/admin/hero/delete', [HeroController::class, 'delete']);

// About Us Module
use Lilyweb\App\Controllers\Admin\AboutController;
Router::get('/admin/about', [AboutController::class, 'index']);
Router::post('/admin/about/save', [AboutController::class, 'save']);

// Services Module
use Lilyweb\App\Controllers\Admin\ServiceController;
Router::get('/admin/services', [ServiceController::class, 'index']);
Router::get('/admin/services/create', [ServiceController::class, 'create']);
Router::get('/admin/services/edit/{id}', [ServiceController::class, 'edit']);
Router::post('/admin/services/save', [ServiceController::class, 'save']);
Router::post('/admin/services/delete', [ServiceController::class, 'delete']);

// Process Steps Module
use Lilyweb\App\Controllers\Admin\ProcessController;
Router::get('/admin/process', [ProcessController::class, 'index']);
Router::get('/admin/process/create', [ProcessController::class, 'create']);
Router::get('/admin/process/edit/{id}', [ProcessController::class, 'edit']);
Router::post('/admin/process/save', [ProcessController::class, 'save']);
Router::post('/admin/process/delete', [ProcessController::class, 'delete']);

// Achievement Stats Module
use Lilyweb\App\Controllers\Admin\StatController;
Router::get('/admin/stats', [StatController::class, 'index']);
Router::get('/admin/stats/create', [StatController::class, 'create']);
Router::get('/admin/stats/edit/{id}', [StatController::class, 'edit']);
Router::post('/admin/stats/save', [StatController::class, 'save']);
Router::post('/admin/stats/delete', [StatController::class, 'delete']);

// Testimonials Module
use Lilyweb\App\Controllers\Admin\TestimonialController;
Router::get('/admin/testimonials', [TestimonialController::class, 'index']);
Router::get('/admin/testimonials/create', [TestimonialController::class, 'create']);
Router::get('/admin/testimonials/edit/{id}', [TestimonialController::class, 'edit']);
Router::post('/admin/testimonials/save', [TestimonialController::class, 'save']);
Router::post('/admin/testimonials/delete', [TestimonialController::class, 'delete']);

// FAQ Module
use Lilyweb\App\Controllers\Admin\FaqController;
Router::get('/admin/faq', [FaqController::class, 'index']);
Router::get('/admin/faq/create', [FaqController::class, 'create']);
Router::get('/admin/faq/edit/{id}', [FaqController::class, 'edit']);
Router::post('/admin/faq/save', [FaqController::class, 'save']);
Router::post('/admin/faq/delete', [FaqController::class, 'delete']);

// Custom Content Pages Module (Bilingual EN/BN)
use Lilyweb\App\Controllers\Admin\PageController as AdminPageController;
Router::get('/admin/pages', [AdminPageController::class, 'index']);
Router::get('/admin/pages/create', [AdminPageController::class, 'create']);
Router::get('/admin/pages/edit/{id}', [AdminPageController::class, 'edit']);
Router::post('/admin/pages/save', [AdminPageController::class, 'save']);
Router::post('/admin/pages/delete', [AdminPageController::class, 'delete']);

// Project Categories Module
use Lilyweb\App\Controllers\Admin\CategoryController;
Router::get('/admin/categories', [CategoryController::class, 'index']);
Router::get('/admin/categories/create', [CategoryController::class, 'create']);
Router::get('/admin/categories/edit/{id}', [CategoryController::class, 'edit']);
Router::post('/admin/categories/save', [CategoryController::class, 'save']);
Router::post('/admin/categories/delete', [CategoryController::class, 'delete']);

// Projects Module
use Lilyweb\App\Controllers\Admin\ProjectController as AdminProjectController;
Router::get('/admin/projects', [AdminProjectController::class, 'index']);
Router::get('/admin/projects/create', [AdminProjectController::class, 'create']);
Router::get('/admin/projects/edit/{id}', [AdminProjectController::class, 'edit']);
Router::post('/admin/projects/save', [AdminProjectController::class, 'save']);
Router::post('/admin/projects/delete', [AdminProjectController::class, 'delete']);

// Public Contact & Consultation Form Handler
use Lilyweb\App\Controllers\ContactController;
Router::post('/contact', [ContactController::class, 'submit']);
Router::post('/consultation', [ContactController::class, 'submit']);

// Media Management Module
use Lilyweb\App\Controllers\Admin\MediaController;
Router::get('/admin/media', [MediaController::class, 'index']);
Router::post('/admin/media/upload', [MediaController::class, 'upload']);
Router::post('/admin/media/quick-upload', [MediaController::class, 'quickUpload']);
Router::get('/admin/media/picker-list', [MediaController::class, 'pickerList']);
Router::post('/admin/media/update', [MediaController::class, 'update']);
Router::post('/admin/media/delete', [MediaController::class, 'delete']);

// Contact Submissions & Lead Inquiries Module
use Lilyweb\App\Controllers\Admin\ContactController as AdminContactController;
Router::get('/admin/contacts', [AdminContactController::class, 'index']);
Router::get('/admin/contacts/export', [AdminContactController::class, 'export']);
Router::get('/admin/contacts/{id}', [AdminContactController::class, 'show']);
Router::post('/admin/contacts/status', [AdminContactController::class, 'updateStatus']);
Router::post('/admin/contacts/delete', [AdminContactController::class, 'delete']);

// Dynamic XML Sitemap & Robots.txt for Google & AI Web Crawlers
use Lilyweb\App\Controllers\SitemapController;
Router::get('/sitemap.xml', [SitemapController::class, 'sitemap']);
Router::get('/robots.txt', [SitemapController::class, 'robots']);

// Settings & Security Module
use Lilyweb\App\Controllers\Admin\SettingController;
Router::get('/admin/settings', [SettingController::class, 'index']);
Router::post('/admin/settings/save', [SettingController::class, 'save']);
Router::post('/admin/settings/owner', [SettingController::class, 'updateOwner']);

// SEO & AI Search Optimization (GEO) Module
use Lilyweb\App\Controllers\Admin\SeoController;
Router::get('/admin/seo', [SeoController::class, 'index']);
Router::post('/admin/seo/save', [SeoController::class, 'save']);

// Backup & Disaster Recovery Module
use Lilyweb\App\Controllers\Admin\BackupController;
Router::get('/admin/backup', [BackupController::class, 'index']);
Router::get('/admin/backups', [BackupController::class, 'index']);
Router::post('/admin/backup/create', [BackupController::class, 'createSql']);
Router::get('/admin/backup/download/{id}', [BackupController::class, 'download']);
Router::post('/admin/backup/restore', [BackupController::class, 'restore']);
Router::post('/admin/backup/delete', [BackupController::class, 'delete']);

// User Documentation & Step-by-Step Guide
use Lilyweb\App\Controllers\Admin\GuideController;
Router::get('/admin/docs', [GuideController::class, 'index']);
Router::get('/admin/guide', [GuideController::class, 'index']);

// Aliases
Router::get('/admin/faqs', [FaqController::class, 'index']);
Router::get('/admin/inquiries', [AdminContactController::class, 'index']);

// Dynamic custom pages - single-segment slugs resolve from the pages table.
// Registered last so all specific static routes keep priority.
Router::get('/{slug}', [PageController::class, 'show']);

// Non-existent routes fall through to the router's 404 handler.
