<?php

// Paksa direktori penyimpanan dan tampilan mengarah ke /tmp yang bisa ditulis di Vercel
$_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
$_ENV['VIEW_COMPILED_PATH'] = '/tmp';

// Meneruskan permintaan dari Vercel ke file index Laravel
require __DIR__ . '/../public/index.php';