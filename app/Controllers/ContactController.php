<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers;

use Lilyweb\Core\Controller;
use Lilyweb\Core\Request;
use Lilyweb\Core\Response;
use Lilyweb\Core\Database;
use Lilyweb\Core\Security;
use Lilyweb\Core\Session;
use Lilyweb\Core\Lang;
use Exception;

final class ContactController extends Controller
{
    /**
     * Handle public contact & consultation form submissions.
     */
    public function submit(): Response
    {        Security::verifyCsrf();

        $ip = Request::ip();

        // Honeypot check - bots fill this field, humans leave it empty
        $website = (string) Request::post('website_url', '');
        if ($website !== '') {
            // Silently reject bot submissions
            $isBn = Lang::isBn();
            Session::flash('success', $isBn 
                ? 'ধন্যবাদ! আপনার কনসালটেশন অনুরোধটি সফলভাবে জমা হয়েছে। আমাদের সিনিয়র আর্কিটেক্ট দ্রুত যোগাযোগ করবেন।' 
                : 'Thank you! Your consultation inquiry has been received. Our senior architect team will contact you shortly.');
            return Response::redirect('/#contact');
        }

        // Rate limiting (max 10 submissions per hour per IP)
        if (Security::isContactRateLimited($ip)) {
            $isBn = Lang::isBn();
            Session::flash('error', $isBn 
                ? 'অনেক বেশি অনুরোধ পাঠানো হয়েছে। অনুগ্রহ করে কিছুক্ষণ অপেক্ষা করুন।' 
                : 'Too many submissions. Please wait a few minutes before trying again.');
            return Response::redirect('/#contact');
        }

        $name = trim((string) Request::post('name', ''));
        $phone = trim((string) Request::post('phone', ''));
        $email = trim((string) Request::post('email', ''));
        $service = trim((string) Request::post('service', ''));
        $location = trim((string) Request::post('location', ''));
        $message = trim((string) Request::post('message', ''));

        if ($name === '' || $phone === '') {
            $isBn = Lang::isBn();
            Session::flash('error', $isBn ? 'অনুগ্রহ করে আপনার নাম এবং মোবাইল নম্বর প্রদান করুন।' : 'Please provide your full name and contact phone number.');
            return Response::redirect('/#contact');
        }

        // Validate email format if provided
        if ($email !== '' && !Security::isValidEmail($email)) {
            $isBn = Lang::isBn();
            Session::flash('error', $isBn ? 'অনুগ্রহ করে একটি বৈধ ইমেইল ঠিকানা প্রদান করুন।' : 'Please provide a valid email address.');
            return Response::redirect('/#contact');
        }

        // Sanitize message length to prevent abuse
        $message = mb_substr($message, 0, 2000);

        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("
                INSERT INTO `lilyweb_contact_submissions`
                (`full_name`, `phone_number`, `email_address`, `service_slug`, `project_location`, `message`, `status`, `ip_address`, `user_agent`)
                VALUES (:name, :phone, :email, :service, :loc, :msg, 'new', :ip, :ua)
            ");
            $stmt->execute([
                ':name' => $name,
                ':phone' => $phone,
                ':email' => $email ?: null,
                ':service' => $service ?: null,
                ':loc' => $location ?: null,
                ':msg' => $message ?: 'Requested consultation callback.',
                ':ip' => $ip,
                ':ua' => Request::userAgent(),
            ]);

            Security::recordContactAttempt($ip);

            $isBn = Lang::isBn();
            Session::flash('success', $isBn 
                ? 'ধন্যবাদ! আপনার কনসালটেশন অনুরোধটি সফলভাবে জমা হয়েছে। আমাদের সিনিয়র আর্কিটেক্ট দ্রুত যোগাযোগ করবেন।' 
                : 'Thank you! Your consultation inquiry has been received. Our senior architect team will contact you shortly.');

        } catch (Exception $e) {
            Session::flash('error', 'A system error occurred. Please call directly at +880 1793 543 898.');
        }

        return Response::redirect('/#contact');
    }

    /**
     * Handle public newsletter subscription.
     */
    public function newsletter(): Response
    {
        $email = trim((string) Request::post('email', ''));
        $isBn = Lang::isBn();

        if ($email === '' || !Security::isValidEmail($email)) {
            Session::flash('error', $isBn ? 'অনুগ্রহ করে একটি সঠিক ইমেইল ঠিকানা দিন।' : 'Please provide a valid email address.');
            return Response::redirect('/#contact');
        }

        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("
                INSERT INTO `lilyweb_contact_submissions`
                (`full_name`, `phone_number`, `email_address`, `service_slug`, `project_location`, `message`, `status`, `ip_address`, `user_agent`)
                VALUES (:name, :phone, :email, 'newsletter', NULL, 'Subscribed to Lily Interiors Newsletter', 'new', :ip, :ua)
            ");
            $stmt->execute([
                ':name' => 'Newsletter Subscriber',
                ':phone' => 'N/A',
                ':email' => $email,
                ':ip' => Request::ip(),
                ':ua' => Request::userAgent(),
            ]);

            Session::flash('success', $isBn 
                ? 'ধন্যবাদ! নিউজলেটার সাবস্ক্রিপশন সফল হয়েছে।' 
                : 'Thank you! You have been successfully subscribed to our newsletter.');
        } catch (\Throwable $e) {
            Session::flash('error', 'Could not process newsletter subscription at this time.');
        }

        return Response::redirect('/#contact');
    }
}
