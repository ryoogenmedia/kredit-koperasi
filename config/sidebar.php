<?php

return [
    [
        'title' => 'Beranda',
        'icon' => 'home',
        'route-name' => 'home',
        'is-active' => 'home',
        'description' => 'Untuk melihat ringkasan aplikasi.',
        'roles' => ['admin', 'user'],
    ],

    [
        'title' => 'Nasabah',
        'description' => 'daftar data nasabah',
        'icon' => 'user-tie',
        'route-name' => 'nasabah.index',
        'is-active' => 'nasabah*',
        'roles' => ['admin'],
        'sub-menus' => [
            [
                'title' => 'Data',
                'description' => 'Melihat daftar nasabah.',
                'route-name' => 'nasabah.index',
                'is-active' => 'nasabah.index',
            ],
            [
                'title' => 'Verfikasi Nasabah',
                'description' => 'Melihat daftar verfikasi nasabah.',
                'route-name' => 'nasabah.verification',
                'is-active' => 'setting.verification',
            ],
        ],
    ],

    [
        'title' => 'Akad',
        'description' => 'daftar data akad nasabah',
        'icon' => 'handshake',
        'route-name' => 'akad.index',
        'is-active' => 'akad*',
        'roles' => ['admin'],
        'sub-menus' => [
            [
                'title' => 'Pinjaman',
                'description' => 'Melihat daftar akad nasabah.',
                'route-name' => 'akad.pinjaman.index',
                'is-active' => 'akad.pinjaman*',
            ],
            [
                'title' => 'Pencairan Dana',
                'description' => 'Melihat daftar pencairan dana nasabah.',
                'route-name' => 'akad.funds',
                'is-active' => 'akad.funds',
            ],
        ],
    ],

    [
        'title' => 'Pengguna',
        'icon' => 'user',
        'route-name' => 'pengguna.index',
        'is-active' => 'pengguna*',
        'description' => 'Untuk kelola data pengguna aplikasi.',
        'roles' => ['admin'],
    ],

    [
        'title' => 'Pengaturan',
        'description' => 'Menampilkan pengaturan aplikasi.',
        'icon' => 'cog',
        'route-name' => 'setting.profile.index',
        'is-active' => 'setting*',
        'roles' => ['admin', 'user'],
        'sub-menus' => [
            [
                'title' => 'Profil',
                'description' => 'Melihat pengaturan profil.',
                'route-name' => 'setting.profile.index',
                'is-active' => 'setting.profile*',
            ],
            [
                'title' => 'Akun',
                'description' => 'Melihat pengaturan akun.',
                'route-name' => 'setting.account.index',
                'is-active' => 'setting.account*',
            ],
        ],
    ],
];
