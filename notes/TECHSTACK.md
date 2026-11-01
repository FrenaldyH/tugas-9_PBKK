# Tech Stack Tugas 9: Agentic AI Platform (skema relasional)

Sumber versi: `tugas-6/composer.json` dan `package.json` (pola yang sama dipakai di sini). Mesin lokal saat dokumen ini
ditulis: PHP 8.5.4, Node v24.18.1, Composer 2.10.3.

## 1. Ringkasan
| Lapisan | Pilihan | Versi | Alasan |
|---|---|---|---|
| Bahasa | PHP | ^8.2 | syarat Laravel 12 |
| Framework | Laravel | ^12.0 | sama dengan tugas-4 dan tugas-6 |
| Auth + scaffold | Laravel Breeze (Blade) | ^2.4 | tugas menyebut "User bawaan Breeze"; tanpa SPA, tidak menambah framework JS |
| ORM | Eloquent | bawaan | fokus tugas: relasi `hasMany`/`belongsTo`/`morphTo` |
| Database (dev) | SQLite | bawaan | tanpa setup; migrasi ditulis portabel |
| Database (opsi) | MySQL | 8.4 via `docker-compose.yml` | bila repo kelompok memakai MySQL; salin `docker-compose.yml` dari tugas-6 |
| Build frontend | Vite + `laravel-vite-plugin` | ^7 / ^2 | bawaan Laravel 12 |
| CSS | Tailwind CSS + `@tailwindcss/forms` | ^3.1 / ^0.5 | dipakai Breeze; token di `DESIGN.md` dipetakan ke `tailwind.config.js` |
| JS ringan | Alpine.js | ^3 | dropdown dan menu Breeze |
| Font | Inter + JetBrains Mono | n/a | gratis; lihat `DESIGN.md` |
| Test | PHPUnit (+ Collision, Mockery) | ^11.5 | sama dengan tugas-6 |
| Data dummy | Faker (`fakerphp/faker`) | ^1.23 | untuk factory |
| Gaya kode | Laravel Pint, preset `psr12` | ^1.24 | rubrik PSR-12 (10%) |
| Bukti N+1 | `barryvdh/laravel-debugbar` (dev) + `Model::preventLazyLoading()` | terbaru yang kompatibel | tugas meminta screenshot Debugbar |
| ERD | Mermaid di `README.md` | n/a | GitHub merender langsung, tanpa alat tambahan |

## 2. Kenapa ini, dan kenapa bukan yang lain
| Keputusan | Alternatif yang ditolak | Alasan menolak |
|---|---|---|
| Breeze Blade | Livewire, Inertia + Vue/React | Antarmuka tidak dinilai rubrik; framework tambahan menambah risiko dan waktu |
| PHPUnit | Pest | Sudah dipakai di tugas-6; tidak ada manfaat tambahan untuk tugas ini |
| SQLite dev | MySQL wajib | MySQL butuh Docker; SQLite cukup untuk membuktikan FK dan cascade |
| Tanpa queue/Redis/API | Sanctum, Horizon | Tugas hanya meminta skema + relasi + kueri; tidak ada fitur asinkron |

## 3. Struktur folder
`[BARU]` file baru, `[UBAH]` file bawaan yang diubah. Nama file migrasi memakai timestamp yang dibuat `artisan`.
```
tugas-9/
├── app/
│   ├── Enums/                         [BARU] MessageRole.php, ToolStatus.php
│   ├── Http/Controllers/
│   │   └── ChatSessionController.php  [BARU] index, show (eager loading bertingkat)
│   ├── Models/
│   │   ├── Concerns/
│   │   │   └── HasToolExecutionLogs.php   [BARU] trait morphMany + hook deleting
│   │   ├── User.php                   [UBAH] chatSessions()
│   │   ├── ChatSession.php            [BARU]
│   │   ├── ChatMessage.php            [BARU]
│   │   ├── ToolExecutionLog.php       [BARU] chatMessage(), loggable()
│   │   ├── SecurityScanner.php        [BARU] agent (target morph)
│   │   ├── MalwareScanner.php         [BARU]
│   │   └── LogAnalyzer.php            [BARU]
│   ├── Policies/
│   │   └── ChatSessionPolicy.php      [BARU] hanya pemilik yang boleh melihat
│   └── Providers/
│       └── AppServiceProvider.php     [UBAH] enforceMorphMap, preventLazyLoading
├── database/
│   ├── factories/                     [BARU] ChatSession, ChatMessage, ToolExecutionLog, 3 agent
│   ├── migrations/
│   │   ├── …_create_chat_sessions_table.php
│   │   ├── …_create_chat_messages_table.php
│   │   ├── …_create_security_scanners_table.php
│   │   ├── …_create_malware_scanners_table.php
│   │   ├── …_create_log_analyzers_table.php
│   │   └── …_create_tool_execution_logs_table.php
│   └── seeders/DatabaseSeeder.php     [UBAH] 5 user × 3 sesi × ±8 pesan + sesi demo tetap
├── resources/views/
│   ├── chat-sessions/
│   │   ├── index.blade.php            [BARU]
│   │   └── show.blade.php             [BARU] halaman target screenshot Debugbar
│   ├── components/                    [BARU] role-label, status-badge, tool-log-table, code-block
│   └── layouts/ (app, guest)          [UBAH] ganti font Figtree → Inter + JetBrains Mono
├── routes/web.php                     [UBAH] grup auth: chat-sessions.index / .show
├── tests/Feature/
│   ├── RelationsTest.php              [BARU]
│   ├── CascadeTest.php                [BARU]
│   ├── PolymorphicLogTest.php         [BARU]
│   └── ChatHistoryQueryTest.php       [BARU]
├── docs/screenshots/                  [BARU] debugbar-sebelum.png, debugbar-sesudah.png
├── notes/                             tugas.txt, PLAN.md, DESIGN.md, TECHSTACK.md
├── pint.json                          [BARU] {"preset": "psr12"}
├── tailwind.config.js                 [UBAH] token DESIGN.md
├── README.md                          [UBAH] ERD Mermaid, cara menjalankan, screenshot, pemetaan rubrik
└── (bawaan Laravel) bootstrap/ config/ public/ storage/ vendor/ .env.example artisan composer.json package.json
```

## 4. Konfigurasi penting
- **`.env`:** `DB_CONNECTION=sqlite` (default). Untuk MySQL: isi `DB_*` dan jalankan `docker compose up -d`.
- **Foreign key di SQLite:** Laravel menyalakan `foreign_key_constraints` secara default; `CascadeTest` yang membuktikannya.
  Jangan matikan `DB_FOREIGN_KEYS`.
- **Debugbar:** hanya tampil bila `APP_DEBUG=true`; paket dipasang dengan `--dev` agar tidak ikut produksi.
- **Pint:** `pint.json` memakai `psr12` (preset bawaan Laravel adalah `laravel`, bukan murni PSR-12).

## 5. Perintah harian
```bash
composer setup                 # install, .env, key, migrate, npm install + build
composer dev                   # server + queue + log + vite (skrip bawaan)
php artisan migrate:fresh --seed
php artisan test               # PHPUnit
vendor/bin/pint --test         # cek PSR-12 tanpa mengubah file
vendor/bin/pint                # perbaiki otomatis
```

## 6. Risiko lingkungan
- **PHP 8.5.4 vs Laravel 12.** Proyek tugas-6 (Laravel 12) sudah terpasang di mesin ini, tetapi setelah scaffold
  jalankan `php artisan test` dan periksa peringatan deprecation PHP 8.5 sebelum lanjut.
- **Versi Tailwind ganda.** `tugas-6/package.json` memuat `tailwindcss ^3.1` **dan** `@tailwindcss/vite ^4`. Setelah
  `npm install` jalankan `npm ls tailwindcss` agar yakin hanya satu versi yang aktif; `tailwind.config.js` bergaya v3.
- **Repo kelompok.** Versi di atas mengikuti tugas-6; bila repo kelompok memakai versi lain (mis. tugas-1: Laravel
  ^13.17), samakan dengan repo itu.
