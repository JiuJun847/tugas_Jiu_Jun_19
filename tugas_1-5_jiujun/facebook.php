<?php
session_start();
require_once 'vendor/autoload.php';

// Ganti dengan App ID dan App Secret Facebook Anda
$appId = '3889332931306225'; // App ID
$appSecret = '2cef881484d18679b617ef54cf2bcdb2'; // App Secret

$fb = new Facebook\Facebook([
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => 'v16.0', // Pastikan menggunakan versi Graph API terbaru
]);

$helper = $fb->getRedirectLoginHelper();

try {
    // Periksa apakah ada token akses
    $accessToken = $helper->getAccessToken();
    if (isset($accessToken)) {
        // Simpan token akses di sesi
        $_SESSION['fb_access_token'] = (string) $accessToken;

        // Ambil informasi pengguna
        $response = $fb->get('/me?fields=id,name,email', $accessToken);
        $user = $response->getGraphUser();

        // Simpan data pengguna di sesi
        $_SESSION['fb_user'] = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getField('email') // Pastikan untuk menangani email yang mungkin null
        ];

        // Redirect ke halaman dashboard atau halaman lain yang diinginkan
        header('Location: dashboard.php');
        exit;
    }
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // Menangani kesalahan dari Graph API
    echo 'Graph API Error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // Menangani kesalahan dari Facebook SDK
    echo 'Facebook SDK Error: ' . $e->getMessage();
    exit;
}

// Jika tidak ada token akses, buat URL login
$permissions = ['email']; // Izin yang diperlukan
$redirectUrl = 'http://localhost/facebook.php'; // Ganti dengan URL callback Anda
$loginUrl = $helper->getLoginUrl($redirectUrl, $permissions);

// Redirect pengguna ke URL login Facebook
header('Location: ' . $loginUrl);
exit;
?>