---
version: alpha
name: AgentChat-design
description: Antarmuka obrolan agent AI dengan tabel log tool. Diadaptasi dari bahasa desain Vercel, yaitu kanvas terang netral, satu aksen hitam, sans + mono. Tampilan chat (bubble, log, badge) dirancang sendiri dari token Vercel karena DESIGN.md Vercel tidak mencakup UI chat. Tanpa logo dan nama merek Vercel.
source: "VoltAgent/awesome-design-md, design-md/vercel/DESIGN.md (MIT). Nilai diekstrak dari CSS publik, tanpa jaminan."

colors:
  primary: "#171717"          # satu-satunya warna CTA
  on-primary: "#ffffff"
  ink: "#171717"
  body: "#4d4d4d"
  mute: "#666666"             # ADAPTASI: sumber #888888 (kontras 3,5:1 di atas putih, gagal AA untuk teks kecil)
  mute-decorative: "#888888"  # hanya untuk ikon/pemisah, jangan untuk teks
  hairline: "#ebebeb"
  hairline-strong: "#a1a1a1"
  canvas: "#ffffff"
  canvas-soft: "#fafafa"
  canvas-soft-2: "#f5f5f5"
  link: "#0070f3"
  link-deep: "#0761d1"
  link-bg-soft: "#d3e5ff"
  error: "#ee0000"
  error-soft: "#f7d4d6"
  error-deep: "#c50000"
  warning: "#f5a623"
  warning-soft: "#ffefcf"
  warning-deep: "#ab570a"
  selection-bg: "#171717"
  selection-fg: "#f2f2f2"

rounded:
  none: 0px
  xs: 4px
  sm: 6px
  md: 8px
  lg: 12px
  pill: 100px
  full: 9999px

spacing:
  xxs: 4px
  xs: 8px
  sm: 12px
  md: 16px
  lg: 24px
  xl: 32px
  2xl: 40px
  3xl: 48px
  4xl: 64px

typography:
  # ADAPTASI: Geist diganti Inter (gratis, ada di Google Fonts / Bunny Fonts); Geist Mono diganti JetBrains Mono
  display-md:  { fontFamily: "Inter, system-ui, sans-serif", fontSize: 24px, fontWeight: 600, lineHeight: 32px, letterSpacing: -0.96px }
  display-sm:  { fontFamily: "Inter, system-ui, sans-serif", fontSize: 20px, fontWeight: 600, lineHeight: 28px, letterSpacing: -0.6px }
  body-lg:     { fontFamily: "Inter, system-ui, sans-serif", fontSize: 18px, fontWeight: 400, lineHeight: 28px }
  body-md:     { fontFamily: "Inter, system-ui, sans-serif", fontSize: 16px, fontWeight: 400, lineHeight: 24px }
  body-md-strong: { fontFamily: "Inter, system-ui, sans-serif", fontSize: 16px, fontWeight: 500, lineHeight: 24px }
  body-sm:     { fontFamily: "Inter, system-ui, sans-serif", fontSize: 14px, fontWeight: 400, lineHeight: 20px, letterSpacing: -0.28px }
  body-sm-strong: { fontFamily: "Inter, system-ui, sans-serif", fontSize: 14px, fontWeight: 500, lineHeight: 20px, letterSpacing: -0.28px }
  caption:     { fontFamily: "Inter, system-ui, sans-serif", fontSize: 12px, fontWeight: 400, lineHeight: 16px }
  caption-mono: { fontFamily: "'JetBrains Mono', ui-monospace, Menlo, monospace", fontSize: 12px, fontWeight: 400, lineHeight: 16px }
  code:        { fontFamily: "'JetBrains Mono', ui-monospace, Menlo, monospace", fontSize: 13px, fontWeight: 400, lineHeight: 20px }
  button-md:   { fontFamily: "Inter, system-ui, sans-serif", fontSize: 14px, fontWeight: 500, lineHeight: 20px }

components:
  # --- dari Vercel (apa adanya) ---
  nav-bar:        { backgroundColor: "{colors.canvas}", textColor: "{colors.ink}", typography: "{typography.body-sm}", height: 64px, padding: "{spacing.sm} {spacing.lg}" }
  form-input:     { backgroundColor: "{colors.canvas}", textColor: "{colors.ink}", borderColor: "{colors.hairline}", typography: "{typography.body-sm}", rounded: "{rounded.sm}", padding: "0px {spacing.sm}", height: 40px }
  card-soft:      { backgroundColor: "{colors.canvas-soft}", textColor: "{colors.ink}", typography: "{typography.body-md}", rounded: "{rounded.md}", padding: "{spacing.lg}" }
  code-block:     { backgroundColor: "{colors.primary}", textColor: "{colors.on-primary}", typography: "{typography.code}", rounded: "{rounded.md}", padding: "{spacing.lg}" }
  # --- ADAPTASI untuk app ---
  button-primary: { backgroundColor: "{colors.primary}", textColor: "{colors.on-primary}", typography: "{typography.button-md}", rounded: "{rounded.sm}", height: 40px, padding: "0px {spacing.md}" }   # sumber: pill 100px untuk CTA marketing; app memakai rounded.sm
  button-secondary: { backgroundColor: "{colors.canvas}", textColor: "{colors.ink}", borderColor: "{colors.hairline}", typography: "{typography.button-md}", rounded: "{rounded.sm}", height: 40px, padding: "0px {spacing.md}" }
  # --- RANCANGAN BARU (tidak ada di Vercel) ---
  session-list-item: { backgroundColor: "{colors.canvas}", borderColor: "{colors.hairline}", rounded: "{rounded.md}", padding: "{spacing.md}", titleTypography: "{typography.body-md-strong}", metaTypography: "{typography.caption-mono}", metaColor: "{colors.mute}", hoverBackground: "{colors.canvas-soft}" }
  role-label:     { typography: "{typography.caption-mono}", textTransform: uppercase, textColor: "{colors.mute}" }
  message-user:   { backgroundColor: "{colors.canvas-soft}", textColor: "{colors.ink}", typography: "{typography.body-md}", rounded: "{rounded.md}", padding: "{spacing.md}" }
  message-assistant: { backgroundColor: "{colors.canvas}", textColor: "{colors.ink}", typography: "{typography.body-md}", padding: "{spacing.md} 0", borderTop: "1px solid {colors.hairline}" }
  message-system: { backgroundColor: "{colors.canvas-soft-2}", textColor: "{colors.body}", typography: "{typography.body-sm}", rounded: "{rounded.md}", padding: "{spacing.sm} {spacing.md}" }
  tool-log-table: { headerBackground: "{colors.canvas-soft}", headerTypography: "{typography.caption-mono}", headerTransform: uppercase, bodyTypography: "{typography.body-sm}", cellPadding: "{spacing.xs} {spacing.sm}", rowBorder: "{colors.hairline}", columns: "TOOL | AGENT | STATUS | DURASI" }
  status-badge-pending: { backgroundColor: "{colors.warning-soft}", textColor: "{colors.warning-deep}", typography: "{typography.caption}", rounded: "{rounded.full}", padding: "0px {spacing.xs}" }
  status-badge-success: { backgroundColor: "{colors.link-bg-soft}", textColor: "{colors.link-deep}", typography: "{typography.caption}", rounded: "{rounded.full}", padding: "0px {spacing.xs}" }
  status-badge-failed:  { backgroundColor: "{colors.error-soft}", textColor: "{colors.error-deep}", typography: "{typography.caption}", rounded: "{rounded.full}", padding: "0px {spacing.xs}" }
  agent-badge:    { backgroundColor: "{colors.canvas-soft}", textColor: "{colors.body}", typography: "{typography.caption-mono}", rounded: "{rounded.full}", padding: "0px {spacing.xs}" }
---

# Desain AgentChat (diadaptasi dari Vercel)

## Ringkasan
Kanvas putih netral dengan **satu aksen hitam** `#171717`. Hierarki dibentuk oleh berat huruf (600 untuk judul,
400/500 untuk isi), ukuran, dan garis tipis `hairline`, bukan oleh warna atau bayangan. Teks teknis (label peran,
header tabel, JSON) memakai font mono. Halaman utama yang dirancang: daftar sesi dan detail sesi (tempat tangkapan
layar Debugbar diambil).

## Warna
- **Satu aksen:** `primary` untuk tombol utama saja. Jangan menambah aksen keenam (aturan sumber).
- **Teks:** `ink` untuk judul dan isi pesan, `body` untuk teks sekunder, `mute` untuk caption.
- **Status:** pending = kuning, success = biru, failed = merah. Biru untuk "success" mengikuti token Vercel
  (`success: #0070f3`). Warna **tidak pernah sendirian**: badge selalu memuat teks `pending` / `success` / `failed`.
- **Kontras (hitungan manual, cek ulang dengan alat kontras):** `body` di atas putih ≈ 8,4:1; `mute` ≈ 5,7:1;
  pasangan badge `*-deep` di atas `*-soft` ≈ 4,5:1, tepat di batas AA, jadi badge memakai 12px berat 500 dan wajib berlabel teks.

## Tipografi
Inter untuk UI, JetBrains Mono untuk label teknis dan kode (keduanya gratis). Skala yang dipakai app:
judul halaman `display-md` (24/600), judul seksi `display-sm`, isi pesan `body-md`, tabel `body-sm`, label peran dan
header tabel `caption-mono` huruf kapital, JSON `code`. `display-xl` (48px) tidak dipakai (itu untuk hero marketing).
Aturan sumber: judul tidak boleh huruf kapital semua, tidak boleh berat 700, dan paragraf tidak boleh memakai font mono.

## Tata letak
- Kolom baca `max-w-3xl` (≈768px) untuk detail sesi agar baris tidak lebih dari ±75 karakter; daftar sesi `max-w-5xl`.
- Jarak memakai skala 4/8/12/16/24/32 dan tidak keluar dari skala.
- Pemisah antar pesan = garis `hairline`, **tanpa** bayangan tebal (aturan sumber: jangan satu bayangan berat pada kartu).
- Tabel log lebih lebar dari layar kecil: bungkus dengan `overflow-x-auto` agar halaman tidak ikut bergulir ke samping.
- Debugbar menutupi bagian bawah halaman: beri `padding-bottom` ekstra (mis. `pb-24`) pada halaman detail saat tangkapan layar.

## Komponen
| Komponen | Bentuk | Catatan |
|---|---|---|
| Navbar | tinggi 64px, putih, garis bawah `hairline` | menata ulang `navigation.blade.php` bawaan Breeze |
| Tombol utama | latar `primary`, teks putih, `rounded.sm`, tinggi 40px | sumber memakai pill untuk CTA marketing; app memakai radius kecil |
| Input form | 40px, border `hairline`, `rounded.sm` | dari Vercel apa adanya |
| Item sesi | kartu border tipis, judul 500, meta mono `mute` | hover: latar `canvas-soft` |
| Pesan `user` | kartu `canvas-soft`, `rounded.md` | label peran `USER` di atasnya |
| Pesan `assistant` | tanpa kartu, garis atas `hairline` | label `ASSISTANT`; log tool ditaruh tepat di bawahnya |
| Pesan `system` / `tool` | `canvas-soft-2`, 14px | tampilan sekunder |
| Tabel log tool | header `canvas-soft` mono kapital; baris dipisah `hairline` | kolom TOOL · AGENT · STATUS · DURASI |
| Badge status | pill, warna per status + teks | lihat Warna |
| Badge agent | pill abu muda, isi alias morph (`security_scanner`) | menampilkan tipe `loggable` |
| Blok JSON | latar `#171717`, JetBrains Mono 13px, `rounded.md` | `<details>` bawaan HTML (tanpa JS) untuk membuka `input`/`result` |

## Halaman
1. **Login / Register / Profil:** bawaan Breeze, hanya di-restyle lewat token (jangan menulis ulang).
2. **`chat-sessions.index`:** daftar sesi milik user (judul, jumlah pesan, waktu), tombol "Sesi baru" bila ada.
3. **`chat-sessions.show`:** judul sesi, deretan pesan, tabel log di bawah pesan `assistant`. Halaman ini target
   tangkapan layar Debugbar sebelum/sesudah.
4. **Keadaan kosong:** satu kalimat + satu tombol (mis. "Belum ada sesi").

## Do & Don't
**Do:** satu aksen `primary` untuk aksi utama; judul sentence-case; label teknis dengan font mono; status selalu
berteks; fokus keyboard terlihat (ring 2px `ink`, offset 2px); hormati `prefers-reduced-motion`.
**Don't:** gradien mesh (khusus hero marketing sumber) · judul huruf kapital semua · berat 700 · bayangan tebal ·
`mute-decorative` untuk teks · paragraf dalam font mono · logo atau nama merek Vercel.

## Pemetaan ke Tailwind (`tailwind.config.js`, Breeze memakai Tailwind 3)
```js
// Design tokens from DESIGN.md mapped onto Tailwind's theme
theme: {
    extend: {
        colors: {
            ink: '#171717',
            body: '#4d4d4d',
            mute: '#666666',
            hairline: '#ebebeb',
            canvas: { DEFAULT: '#ffffff', soft: '#fafafa', soft2: '#f5f5f5' },
        },
        fontFamily: {
            sans: ['Inter', ...defaultTheme.fontFamily.sans],
            mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
        },
    },
},
```
Ganti tautan font Figtree di `resources/views/layouts/app.blade.php` dan `guest.blade.php` dengan Inter 400/500/600 dan
JetBrains Mono 400 (Bunny Fonts atau Google Fonts).

## Catatan penyimpangan dari sumber
| Penyimpangan | Alasan |
|---|---|
| `mute` `#888888` → `#666666` | kontras di atas putih hanya ≈3,5:1 (gagal AA untuk teks kecil) |
| Geist → Inter, Geist Mono → JetBrains Mono | status lisensi Geist tidak terverifikasi dari sumber primer; Inter dan JetBrains Mono jelas gratis |
| Tombol pill → `rounded.sm` | pill dipakai sumber untuk CTA marketing; app lebih padat |
| Komponen chat, tabel log, badge | tidak ada di DESIGN.md Vercel; dirancang dari token Vercel |
