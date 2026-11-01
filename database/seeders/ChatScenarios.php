<?php

namespace Database\Seeders;

use App\Enums\MessageRole;
use App\Enums\ToolStatus;

// Hand-written chat transcripts used by DatabaseSeeder, so the demo data reads like a real security assistant
class ChatScenarios
{
    /**
     * Each scenario has a title, how many hours ago it started, and its messages
     * as [role, content, tool logs]. Tool logs are only given on assistant replies.
     *
     * @return array<string, array{title: string, hours_ago: int, messages: array<int, array<int, mixed>>}>
     */
    public static function all(): array
    {
        return [
            // The fixed-size demo session: exactly 10 messages and 6 tool logs
            'audit' => [
                'title' => 'Audit keamanan web-prod-01',
                'hours_ago' => 3,
                'messages' => [
                    [MessageRole::User, 'Tolong audit cepat server web-prod-01 (198.51.100.20). Mulai dari port yang terbuka dan sertifikat SSL-nya.'],
                    [MessageRole::Assistant, 'Pemindaian selesai. Port 22 (SSH), 80 (HTTP), dan 443 (HTTPS) terbuka, ini wajar untuk sebuah web server. Sertifikat SSL masih valid, tetapi akan kedaluwarsa dalam 12 hari, jadi sebaiknya segera diperpanjang.', [
                        self::log('security_scanner', 'scan_ports', ['host' => '198.51.100.20', 'ports' => '1-1024'], ['open_ports' => [22, 80, 443], 'filtered' => 0], ToolStatus::Success, 1840),
                        self::log('security_scanner', 'check_ssl_certificate', ['host' => 'web-prod-01.example.com'], ['issuer' => "Let's Encrypt R11", 'valid' => true, 'expires_in_days' => 12], ToolStatus::Success, 420),
                    ]],
                    [MessageRole::User, 'Ada kerentanan yang diketahui pada versi OpenSSL yang terpasang?'],
                    [MessageRole::Assistant, 'Server memakai OpenSSL 1.1.1k. Versi ini terdampak CVE-2022-0778 (severity tinggi): sertifikat yang dirancang khusus dapat membuat proses parsing masuk ke infinite loop. Disarankan upgrade ke 1.1.1n atau yang lebih baru.', [
                        self::log('security_scanner', 'check_cve', ['package' => 'openssl', 'version' => '1.1.1k'], ['cves' => ['CVE-2022-0778'], 'severity' => 'high', 'fixed_in' => '1.1.1n'], ToolStatus::Success, 960),
                    ]],
                    [MessageRole::User, 'Oke, dicatat. Sekarang cek apakah ada upaya brute force SSH dalam 24 jam terakhir.'],
                    [MessageRole::Assistant, 'Ada indikasi kuat. Log autentikasi 24 jam terakhir mencatat 1.843 login gagal, dan 1.521 di antaranya berasal dari satu alamat IP (203.0.113.45) yang terus mencoba akun root. Saya sarankan memblokir IP tersebut dan menonaktifkan login root lewat SSH.', [
                        self::log('log_analyzer', 'analyze_auth_log', ['file' => '/var/log/auth.log', 'window' => '24h'], ['failed_logins' => 1843, 'top_sources' => [['ip' => '203.0.113.45', 'attempts' => 1521, 'target_user' => 'root']]], ToolStatus::Success, 2310),
                    ]],
                    [MessageRole::User, 'Ada file mencurigakan di folder upload?'],
                    [MessageRole::Assistant, 'Ada satu. Pemindaian /var/www/uploads menemukan faktur_oktober.pdf.exe yang terdeteksi sebagai Trojan.Generic.KD.4821. Ekstensi ganda seperti ini lazim dipakai untuk menyamarkan program berbahaya sebagai dokumen. File sebaiknya dikarantina dan akun pengunggahnya diperiksa.', [
                        self::log('malware_scanner', 'scan_file', ['path' => '/var/www/uploads/faktur_oktober.pdf.exe'], ['verdict' => 'malicious', 'signature' => 'Trojan.Generic.KD.4821', 'size_bytes' => 482304], ToolStatus::Success, 5200),
                    ]],
                    [MessageRole::User, 'Terakhir, cek hash file itu di database ancaman.'],
                    [MessageRole::Assistant, 'Pengecekan hash masih berjalan karena layanan database ancaman belum membalas. Hasilnya akan saya laporkan begitu selesai.', [
                        self::log('malware_scanner', 'check_hash', ['sha256' => '3a7bd3e2360a3d29eea436fcfb7e44c735d117c42d1c1835420b6b9942dd4f1b'], null, ToolStatus::Pending, null),
                    ]],
                ],
            ],

            // Shows a system message and a failed tool call that succeeds on retry
            'ssl' => [
                'title' => 'Pengecekan sertifikat SSL shop.example.co.id',
                'hours_ago' => 26,
                'messages' => [
                    [MessageRole::System, 'Kamu adalah asisten keamanan untuk tim infrastruktur. Jawab ringkas dan selalu sertakan langkah tindak lanjut.'],
                    [MessageRole::User, 'Cek sertifikat SSL untuk shop.example.co.id.'],
                    [MessageRole::Assistant, 'Pengecekan gagal karena koneksi ke host kehabisan waktu setelah 5 detik. Kemungkinan host tidak terjangkau dari jaringan scanner. Tindak lanjut: periksa DNS dan aturan firewall, lalu beri tahu saya untuk mengulang pengecekan.', [
                        self::log('security_scanner', 'check_ssl_certificate', ['host' => 'shop.example.co.id'], null, ToolStatus::Failed, 5000, 'Connection timed out after 5000 ms'),
                    ]],
                    [MessageRole::User, 'Firewall sudah dibuka, coba lagi.'],
                    [MessageRole::Assistant, "Berhasil. Sertifikat diterbitkan oleh Let's Encrypt R11 dan berlaku 63 hari lagi, dengan rantai sertifikat yang lengkap. Tidak ada tindak lanjut sampai sekitar 30 hari sebelum kedaluwarsa.", [
                        self::log('security_scanner', 'check_ssl_certificate', ['host' => 'shop.example.co.id'], ['issuer' => "Let's Encrypt R11", 'valid' => true, 'expires_in_days' => 63, 'chain_complete' => true], ToolStatus::Success, 380),
                    ]],
                ],
            ],

            // A session without any tool call
            'weekly' => [
                'title' => 'Ringkasan temuan mingguan',
                'hours_ago' => 50,
                'messages' => [
                    [MessageRole::User, 'Buatkan ringkasan temuan keamanan minggu ini untuk laporan mingguan.'],
                    [MessageRole::Assistant, "Ringkasan minggu ini:\n- 1 file berbahaya dikarantina (Trojan.Generic.KD.4821)\n- 1.843 percobaan brute force SSH, sumber utamanya direkomendasikan untuk diblokir\n- 1 sertifikat SSL akan kedaluwarsa dalam 12 hari\n\nTindak lanjut: perbarui OpenSSL dan perpanjang sertifikat web-prod-01."],
                    [MessageRole::User, 'Ringkas jadi satu kalimat untuk slide presentasi.'],
                    [MessageRole::Assistant, 'Minggu ini satu malware berhasil dikarantina, sementara brute force SSH dan sertifikat yang hampir kedaluwarsa masih perlu ditindaklanjuti.'],
                ],
            ],

            'traffic' => [
                'title' => 'Lonjakan error 404 di api-gateway',
                'hours_ago' => 8,
                'messages' => [
                    [MessageRole::User, 'Trafik 404 di api-gateway naik tajam sejak jam 02.00. Bisa dicek polanya?'],
                    [MessageRole::Assistant, 'Saya analisis access log nginx sejak 02.00. Ada 12.480 respons 404, dan 94% di antaranya menuju /wp-login.php dan /xmlrpc.php. Pola ini khas bot yang memindai WordPress. Server ini tidak memakai WordPress, jadi tidak ada risiko langsung.', [
                        self::log('log_analyzer', 'analyze_access_log', ['file' => '/var/log/nginx/access.log', 'since' => '02:00', 'status' => 404], ['total' => 12480, 'top_paths' => [['path' => '/wp-login.php', 'count' => 7120], ['path' => '/xmlrpc.php', 'count' => 4630]]], ToolStatus::Success, 3100),
                    ]],
                    [MessageRole::User, 'Apakah perlu diblokir?'],
                    [MessageRole::Assistant, 'Disarankan. Subnet 198.51.100.0/24 menyumbang sebagian besar permintaan, jadi saya cek reputasi alamat teratasnya (198.51.100.77). Skornya tinggi dan sudah dilaporkan sebagai scanner oleh 341 pelapor.', [
                        self::log('security_scanner', 'check_ip_reputation', ['ip' => '198.51.100.77'], ['abuse_score' => 87, 'category' => 'scanner', 'reports' => 341], ToolStatus::Success, 640),
                    ]],
                    [MessageRole::User, 'Oke, tolong blokir subnet itu.'],
                    [MessageRole::Assistant, 'Saya tidak punya izin untuk mengubah aturan firewall, jadi pemblokiran perlu dilakukan tim infrastruktur. Usulan aturannya: deny dari 198.51.100.0/24 pada api-gateway. Saya bisa menyiapkan tiketnya bila diperlukan.'],
                ],
            ],

            'antivirus' => [
                'title' => 'Peringatan antivirus di laptop finance-07',
                'hours_ago' => 5,
                'messages' => [
                    [MessageRole::User, 'Antivirus di laptop finance-07 memunculkan peringatan setelah membuka lampiran email. Bisa dicek filenya?'],
                    [MessageRole::Assistant, 'Saya pindai lampiran di folder Downloads. slip_gaji_november.xlsm terdeteksi mencurigakan karena berisi makro yang mengunduh berkas dari alamat luar. Jangan aktifkan makro, dan putuskan laptop dari jaringan sampai pemeriksaan selesai.', [
                        self::log('malware_scanner', 'scan_file', ['path' => 'C:\\Users\\finance\\Downloads\\slip_gaji_november.xlsm'], ['verdict' => 'suspicious', 'signature' => 'Macro.Downloader.Gen', 'macro_urls' => ['http://203.0.113.90/payload.bin']], ToolStatus::Success, 3870),
                    ]],
                    [MessageRole::User, 'Sudah saya putus dari jaringan. Apa langkah berikutnya?'],
                    [MessageRole::Assistant, 'Bagus. Langkah berikutnya: (1) simpan file tersebut sebagai barang bukti, (2) jalankan pemindaian penuh pada laptop, (3) konfirmasi ke pengirim email lewat saluran lain, dan (4) ganti kata sandi akun yang dipakai di laptop itu.'],
                ],
            ],
        ];
    }

    /**
     * Build one tool log entry; agent is the morph alias of the agent that ran it.
     *
     * @return array<string, mixed>
     */
    private static function log(string $agent, string $tool, array $input, ?array $result, ToolStatus $status, ?int $durationMs, ?string $error = null): array
    {
        return compact('agent', 'tool', 'input', 'result', 'status', 'durationMs', 'error');
    }
}
