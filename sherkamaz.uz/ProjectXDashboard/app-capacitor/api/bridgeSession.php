<?php

session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => 'www.sherkamaz.uz',
    'secure' => true,       // HTTPS only
    'httponly' => true,
    'samesite' => 'None'    // Required for cross-origin
]);
?>