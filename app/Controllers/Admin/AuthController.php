<?php

declare(strict_types=1);

namespace Lilyweb\App\Controllers\Admin;

use Lilyweb\Core\Controller;
use Lilyweb\Core\Request;
use Lilyweb\Core\Response;
use Lilyweb\Core\Auth;
use Lilyweb\Core\Security;
use Lilyweb\Core\Session;

final class AuthController extends Controller
{
    /**
     * Show the owner login screen.
     */
    public function showLogin(array $params = []): Response
    {
        if (Auth::check()) {
            return Response::redirect('/admin');
        }

        $error = Session::getFlash('error');
        $success = Session::getFlash('success');
        Session::flush();

        return $this->render('admin.auth.login', [
            'pageTitle' => 'Owner Login — Lily Interiors CMS',
            'error' => $error,
            'success' => $success,
        ]);
    }

    /**
     * Process owner login submission.
     */
    public function login(array $params = []): Response
    {
        Security::verifyCsrf();

        $login = (string) Request::post('login', '');
        $password = (string) Request::post('password', '');
        $ip = (string) Request::server('REMOTE_ADDR', '127.0.0.1');

        if (trim($login) === '' || trim($password) === '') {
            Session::flash('error', 'Please enter your username/email and password.');
            return Response::redirect('/admin/login');
        }

        $result = Auth::attempt($login, $password, $ip);

        if ($result['success']) {
            Session::flash('success', $result['message']);
            return Response::redirect('/admin');
        }

        Session::flash('error', $result['message']);
        return Response::redirect('/admin/login');
    }

    /**
     * Process owner logout.
     */
    public function logout(array $params = []): Response
    {
        Auth::logout();
        Session::flash('success', 'You have been safely logged out.');
        return Response::redirect('/admin/login');
    }
}
