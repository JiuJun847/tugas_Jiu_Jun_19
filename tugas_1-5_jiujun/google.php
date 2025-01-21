<?php
require 'vendor/autoload.php';

session_start();

// Konfigurasi Google Client
$client_id = "16412090485-tq5rcdagbt9o4l7i5cq2dvhqcar4bl0q.apps.googleusercontent.com";
$client_secret = "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";
$redirect_uri = "http://localhost/latihan_19_jiu%20jun/tugas_1-5_jiujun/signup-opsi.html";

// Inisialisasi Google Client
$client = new Google\Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");

// Tangani proses login
if (!isset($_GET['code'])) {
    // Redirect ke halaman login Google
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit();
} else {
    // Tukar kode otorisasi dengan token akses
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Ambil data pengguna
    $google_service = new Google\Service\Oauth2($client);
    $user_info = $google_service->userinfo->get();

    // Simpan data pengguna ke sesi
    $_SESSION['user'] = [
        'id' => $user_info->id,
        'name' => $user_info->name,
        'email' => $user_info->email,
        'picture' => $user_info->picture,
    ];

    // Redirect ke halaman dashboard
    header('Location: dashboard.php');
    exit();
}