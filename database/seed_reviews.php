<?php
/**
 * Seed 100+ reviews per hotel for all hotels in the database.
 * Run: php database/seed_reviews.php
 */
$cfg = require __DIR__ . '/../config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$cfg['host']};dbname={$cfg['dbname']};charset={$cfg['charset']}",
        $cfg['username'], $cfg['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("DB error: " . $e->getMessage() . "\n");
}

// ── 1. Fake users pool ──────────────────────────────────────────────────
$fakeUsers = [
    ['name'=>'Andi Pratama',      'email'=>'andi.pratama@gmail.com'],
    ['name'=>'Siti Rahayu',       'email'=>'siti.rahayu@gmail.com'],
    ['name'=>'Budi Santoso',      'email'=>'budi.santoso@yahoo.com'],
    ['name'=>'Dewi Anggraini',    'email'=>'dewi.anggraini@gmail.com'],
    ['name'=>'Rizky Firmansyah',  'email'=>'rizky.firmansyah@hotmail.com'],
    ['name'=>'Nurul Hidayah',     'email'=>'nurul.hidayah@gmail.com'],
    ['name'=>'Fajar Setiawan',    'email'=>'fajar.setiawan@gmail.com'],
    ['name'=>'Wulandari Putri',   'email'=>'wulandari.putri@gmail.com'],
    ['name'=>'Hendra Gunawan',    'email'=>'hendra.gunawan@gmail.com'],
    ['name'=>'Lestari Ningrum',   'email'=>'lestari.ningrum@gmail.com'],
    ['name'=>'Dimas Prayoga',     'email'=>'dimas.prayoga@gmail.com'],
    ['name'=>'Rini Sulistyowati', 'email'=>'rini.sulistyowati@yahoo.com'],
    ['name'=>'Agus Wijaya',       'email'=>'agus.wijaya@gmail.com'],
    ['name'=>'Mega Pertiwi',      'email'=>'mega.pertiwi@gmail.com'],
    ['name'=>'Yusuf Hakim',       'email'=>'yusuf.hakim@gmail.com'],
    ['name'=>'Intan Permatasari', 'email'=>'intan.permatasari@gmail.com'],
    ['name'=>'Bagas Wicaksono',   'email'=>'bagas.wicaksono@gmail.com'],
    ['name'=>'Fitri Handayani',   'email'=>'fitri.handayani@gmail.com'],
    ['name'=>'Galih Saputra',     'email'=>'galih.saputra@gmail.com'],
    ['name'=>'Ayu Kusuma',        'email'=>'ayu.kusuma@gmail.com'],
    ['name'=>'Tegar Prasetyo',    'email'=>'tegar.prasetyo@gmail.com'],
    ['name'=>'Nadia Ramadhani',   'email'=>'nadia.ramadhani@gmail.com'],
    ['name'=>'Wahyu Hidayat',     'email'=>'wahyu.hidayat@gmail.com'],
    ['name'=>'Cindy Aulia',       'email'=>'cindy.aulia@gmail.com'],
    ['name'=>'Reza Mahendra',     'email'=>'reza.mahendra@gmail.com'],
    ['name'=>'Ismi Oktaviani',    'email'=>'ismi.oktaviani@gmail.com'],
    ['name'=>'Arif Rahman',       'email'=>'arif.rahman@gmail.com'],
    ['name'=>'Yeni Safitri',      'email'=>'yeni.safitri@gmail.com'],
    ['name'=>'Faisal Akbar',      'email'=>'faisal.akbar@gmail.com'],
    ['name'=>'Dian Puspita',      'email'=>'dian.puspita@gmail.com'],
    ['name'=>'Hendri Kurniawan',  'email'=>'hendri.kurniawan@gmail.com'],
    ['name'=>'Lia Maharani',      'email'=>'lia.maharani@gmail.com'],
    ['name'=>'Kevin Santana',     'email'=>'kevin.santana@gmail.com'],
    ['name'=>'Yuliana Dewi',      'email'=>'yuliana.dewi@gmail.com'],
    ['name'=>'Andika Putra',      'email'=>'andika.putra@gmail.com'],
    ['name'=>'Rara Aulia',        'email'=>'rara.aulia@gmail.com'],
    ['name'=>'Imam Fauzi',        'email'=>'imam.fauzi@gmail.com'],
    ['name'=>'Sari Melati',       'email'=>'sari.melati@gmail.com'],
    ['name'=>'Yoga Pratama',      'email'=>'yoga.pratama@gmail.com'],
    ['name'=>'Mila Kusumawati',   'email'=>'mila.kusumawati@gmail.com'],
    ['name'=>'Surya Darma',       'email'=>'surya.darma@gmail.com'],
    ['name'=>'Anggi Lestari',     'email'=>'anggi.lestari@gmail.com'],
    ['name'=>'Hanafi Basri',      'email'=>'hanafi.basri@gmail.com'],
    ['name'=>'Triana Wulandari',  'email'=>'triana.wulandari@gmail.com'],
    ['name'=>'Akbar Maulana',     'email'=>'akbar.maulana@gmail.com'],
    ['name'=>'Risma Indah',       'email'=>'risma.indah@gmail.com'],
    ['name'=>'Denny Hamdani',     'email'=>'denny.hamdani@gmail.com'],
    ['name'=>'Putri Rahayu',      'email'=>'putri.rahayu@gmail.com'],
    ['name'=>'Eko Setiabudi',     'email'=>'eko.setiabudi@gmail.com'],
    ['name'=>'Nuraini Hasanah',   'email'=>'nuraini.hasanah@gmail.com'],
];

// Insert fake users and collect their IDs
$defaultPass = password_hash('user123', PASSWORD_BCRYPT);
$userIds = [];

// Get existing user IDs first
$existing = $pdo->query("SELECT id FROM users")->fetchAll(PDO::FETCH_COLUMN);
$userIds = array_values($existing);

$checkStmt  = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$insertUser = $pdo->prepare(
    "INSERT INTO users (name, email, password, phone, role, active) VALUES (?, ?, ?, ?, 'user', 1)"
);

foreach ($fakeUsers as $i => $u) {
    $checkStmt->execute([$u['email']]);
    $existing = $checkStmt->fetchColumn();
    if ($existing) {
        $userIds[] = (int)$existing;
    } else {
        $phone = '08' . str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
        $insertUser->execute([$u['name'], $u['email'], $defaultPass, $phone]);
        $userIds[] = (int)$pdo->lastInsertId();
    }
}
$userIds = array_unique($userIds);
echo "Total reviewer pool: " . count($userIds) . " users\n";

// ── 2. Review comment bank ──────────────────────────────────────────────
$commentsByRating = [
    5 => [
        "Pengalaman menginap yang luar biasa! Kamar sangat bersih dan nyaman, pelayanan staf sangat ramah dan profesional.",
        "Hotel terbaik yang pernah saya kunjungi. Fasilitas lengkap, pemandangan indah, dan sarapan prasmanan yang sangat lezat.",
        "Sangat puas dengan pelayanan hotel ini. Staf sangat membantu dan responsif terhadap setiap permintaan kami.",
        "Kamar mewah dengan desain interior yang elegan. Tempat tidur sangat nyaman, tidur terasa seperti di surga.",
        "Lokasi sangat strategis, dekat dengan pusat perbelanjaan dan tempat wisata. Akses mudah ke mana-mana.",
        "Kolam renang yang bersih dan terawat, gym lengkap dengan peralatan modern. Sangat worth it untuk harganya!",
        "Sarapan buffet dengan pilihan menu yang sangat beragam, dari masakan Indonesia hingga Western. Enak semua!",
        "Check-in dan check-out proses sangat cepat dan efisien. Staf sangat ramah dan membantu membawakan barang.",
        "View dari kamar sangat memukau! Bisa melihat pemandangan kota dari ketinggian. Sangat indah di malam hari.",
        "Fasilitas spa dan wellness center sangat memuaskan. Terapis sangat profesional dan terampil.",
        "Hotel ini melebihi ekspektasi saya. Semua detail diperhatikan dengan sangat baik. Pasti akan kembali lagi!",
        "Pelayanan room service cepat dan makanannya lezat. Minibar lengkap dengan berbagai pilihan minuman.",
        "Kamar mandi dengan bathtub dan shower yang mewah. Amenities hotel sangat lengkap dan berkualitas tinggi.",
        "Parkir luas dan gratis, keamanan 24 jam, CCTV di semua sudut. Merasa sangat aman selama menginap.",
        "WiFi sangat cepat dan stabil di seluruh area hotel. Sangat membantu untuk bekerja dari hotel.",
        "Restoran hotel menyajikan hidangan autentik yang sangat lezat dengan chef berpengalaman.",
        "Sangat cocok untuk bulan madu! Dekorasi kamar romantis, pelayanan personal yang sangat istimewa.",
        "Konferensi bisnis berjalan lancar berkat fasilitas meeting room yang lengkap dan teknologi canggih.",
        "Anak-anak sangat senang dengan kids club dan kolam renang anak. Family trip yang sempurna!",
        "Harga sangat sepadan dengan kualitas pelayanan dan fasilitas yang diberikan. Highly recommended!",
    ],
    4 => [
        "Hotel yang bagus dengan lokasi strategis. Pelayanan staf cukup memuaskan, hanya sedikit lambat saat check-in.",
        "Kamar bersih dan nyaman, fasilitas lengkap. Sarapan enak meski pilihan menunya agak terbatas.",
        "Secara keseluruhan sangat memuaskan. Kolam renang bersih, gym ada, restoran enak. Recommended!",
        "Staf ramah dan helpful. Kamar sedikit kecil tapi desainnya modern dan fungsional. Akan kembali lagi.",
        "Lokasi dekat bandara, sangat praktis untuk perjalanan bisnis. Shuttle service tepat waktu.",
        "Tempat tidur sangat nyaman, tidur nyenyak sepanjang malam. AC dingin dan tidak berisik.",
        "Sarapan cukup memuaskan dengan pilihan yang lumayan bervariasi. Kopi dan teh tersedia sepanjang hari.",
        "Kolam renang bersih dan tidak terlalu ramai. Bisa berenang dengan nyaman dan santai.",
        "Pelayanan concierge sangat membantu dalam merekomendasikan tempat wisata dan restoran sekitar.",
        "Kamar mandi modern dengan shower tekanan air yang bagus. Amenities cukup lengkap dan berkualitas.",
        "WiFi lumayan cepat untuk keperluan sehari-hari. Sinyal kuat di kamar maupun area umum hotel.",
        "Harga cukup terjangkau untuk kualitas yang diberikan. Value for money yang baik.",
        "Pemandangan dari kamar cukup bagus, bisa melihat taman dan kolam renang dari balkon.",
        "Proses check-in mudah meskipun agak antri. Staf tetap ramah meski sedang sibuk.",
        "Restoran hotel menyajikan menu yang cukup enak. Porsi besar dan harga masih wajar.",
        "Fasilitas laundry cepat dan hasil bersih. Biaya laundry standar sesuai tarif hotel bintang empat.",
        "Keamanan hotel terjaga dengan baik. Merasa aman dan nyaman selama menginap.",
        "Transportasi dari dan ke bandara sangat mudah diatur melalui resepsionis hotel.",
        "Dekorasi hotel modern dan stylish. Instagram-worthy di banyak sudut hotel.",
        "Overall pengalaman menginap yang menyenangkan. Pasti akan merekomendasikan ke teman dan keluarga.",
    ],
    3 => [
        "Hotel biasa-biasa saja untuk harganya. Fasilitas standar, tidak ada yang terlalu istimewa.",
        "Kamar cukup bersih tapi sedikit lembab. AC berisik mengganggu tidur di malam hari.",
        "Lokasi strategis tapi parkir sangat terbatas dan susah. Perlu membayar parkir di luar.",
        "Sarapan pilihan sangat terbatas, selalu makanan yang sama setiap harinya. Perlu lebih bervariasi.",
        "Staf cukup ramah tapi agak lama merespons permintaan tamu. Room service butuh waktu lama.",
        "Kolam renang kecil dan sering penuh sesak terutama di akhir pekan. Kurang nyaman.",
        "WiFi lambat dan sering terputus terutama di jam-jam sibuk. Perlu ditingkatkan kualitasnya.",
        "Kamar mandi perlu direnovasi, keran sudah agak berkarat. Amenities standar dan biasa saja.",
        "Harga terlalu mahal untuk fasilitas yang diberikan. Ada hotel lain dengan harga sama tapi lebih baik.",
        "Suara dari luar lumayan keras, kurang kedap suara. Agak terganggu dengan kebisingan sekitar.",
        "View kamar standar, menghadap dinding gedung tetangga. Minta kamar menghadap kolam lebih baik.",
        "Proses check-out cukup lama. Harus antri cukup panjang di meja resepsionis.",
        "Makanan restoran cukup enak tapi porsinya kecil untuk harganya. Perlu ditingkatkan.",
        "Kebersihan kamar cukup, tapi ada beberapa spot yang terlewat dibersihkan.",
        "Fasilitas gym ada tapi peralatannya sudah agak tua dan kurang terawat dengan baik.",
        "Akses internet lambat, susah untuk video call. Perlu upgrade koneksi internetnya.",
        "Pelayanan standar, tidak ada yang terlalu berkesan. Biasa seperti hotel pada umumnya.",
        "Posisi kamar di lantai bawah, kurang privasi. Sebaiknya minta kamar di lantai atas.",
        "Harga sarapan terpisah cukup mahal. Lebih baik sarapan di luar yang lebih murah.",
        "Overall cukup memuaskan untuk menginap singkat. Tidak terlalu banyak komplain.",
    ],
    2 => [
        "Mengecewakan untuk harganya yang cukup mahal. Fasilitas tidak sesuai dengan yang dijanjikan.",
        "Kamar kotor saat pertama check-in, ada noda di sprei. Harus minta ganti dua kali.",
        "AC rusak dan butuh 3 jam untuk diperbaiki. Sangat tidak nyaman di cuaca panas seperti ini.",
        "Staf tidak ramah dan tidak membantu saat ada masalah. Merasa tidak dihargai sebagai tamu.",
        "Sarapan sangat mengecewakan, pilihan sedikit dan tidak segar. Kualitas di bawah ekspektasi.",
        "Kolam renang sangat kotor, tidak terawat. Tidak berani berenang karena airnya keruh.",
        "WiFi tidak berfungsi sama sekali selama dua hari. Komplain tidak ditanggapi dengan serius.",
        "Kamar mandi bocor dan lantai terus basah. Handuk tipis dan tidak menyerap dengan baik.",
        "Harga sangat tidak sebanding dengan kualitas yang diberikan. Jauh di bawah ekspektasi.",
        "Kebisingan dari kamar sebelah sangat mengganggu tidur. Dinding sangat tipis dan tidak kedap suara.",
    ],
    1 => [
        "Sangat mengecewakan! Kamar sangat kotor dan berbau tidak sedap. Tidak akan kembali lagi.",
        "Pelayanan sangat buruk, staf tidak peduli dengan keluhan tamu. Sangat tidak profesional.",
        "Fasilitas tidak sesuai foto di website. Tertipu dengan iklan yang menyesatkan dan tidak jujur.",
        "AC tidak berfungsi, lift rusak, dan air panas tidak ada. Kondisi hotel sangat memprihatinkan.",
        "Sangat tidak recommended! Uang terbuang sia-sia untuk pengalaman menginap yang sangat buruk ini.",
    ],
];

// ── 3. Build weighted rating distribution per hotel tier ────────────────
// Distribution: [rating => weight] – will be used to pick random ratings
// Most hotels should be 4-5 stars weighted by their rating_avg
function pickRating(float $avgRating): int {
    // Build probability based on hotel's average rating
    if ($avgRating >= 4.7) {
        $weights = [5=>55, 4=>35, 3=>7, 2=>2, 1=>1];
    } elseif ($avgRating >= 4.3) {
        $weights = [5=>35, 4=>45, 3=>15, 2=>4, 1=>1];
    } elseif ($avgRating >= 4.0) {
        $weights = [5=>20, 4=>45, 3=>25, 2=>8, 1=>2];
    } else {
        $weights = [5=>10, 4=>30, 3=>35, 2=>18, 1=>7];
    }

    $pool = [];
    foreach ($weights as $r => $w) {
        for ($i = 0; $i < $w; $i++) $pool[] = $r;
    }
    return $pool[array_rand($pool)];
}

// ── 4. Fetch all hotels ─────────────────────────────────────────────────
$hotels = $pdo->query("SELECT id, name, rating_avg FROM hotels ORDER BY id")->fetchAll(PDO::FETCH_OBJ);
$totalHotels = count($hotels);
echo "Hotels to seed: $totalHotels\n";

// ── 5. Delete existing reviews (fresh start) ───────────────────────────
$pdo->exec("DELETE FROM reviews");
echo "Old reviews cleared.\n";

// ── 6. Insert reviews ──────────────────────────────────────────────────
$insertReview = $pdo->prepare(
    "INSERT INTO reviews (user_id, hotel_id, rating, comment, created_at) VALUES (?, ?, ?, ?, ?)"
);
$updateRating = $pdo->prepare(
    "UPDATE hotels SET rating_avg = ? WHERE id = ?"
);

$totalInserted = 0;
$totalUserIds  = count($userIds);
$reviewsPerHotel = 110; // slightly more than 100 to ensure minimum 100

// Start timestamp spread: reviews over the past 2 years
$now      = time();
$twoYears = 2 * 365 * 24 * 3600;

foreach ($hotels as $hotel) {
    $ratingSum   = 0;
    $ratingCount = 0;
    $usedUsers   = []; // track which users reviewed this hotel

    for ($i = 0; $i < $reviewsPerHotel; $i++) {
        // Pick a user (avoid same user reviewing same hotel twice)
        $attempts = 0;
        do {
            $userId = $userIds[array_rand($userIds)];
            $attempts++;
        } while (in_array($userId, $usedUsers) && $attempts < 20);
        $usedUsers[] = $userId;

        $rating  = pickRating((float)$hotel->rating_avg);
        $comment = $commentsByRating[$rating][array_rand($commentsByRating[$rating])];

        // Random timestamp in the past 2 years
        $createdAt = date('Y-m-d H:i:s', $now - rand(0, $twoYears));

        $insertReview->execute([$userId, $hotel->id, $rating, $comment, $createdAt]);

        $ratingSum   += $rating;
        $ratingCount++;
        $totalInserted++;
    }

    // Update hotel rating_avg based on actual inserted reviews
    $newAvg = round($ratingSum / $ratingCount, 1);
    $updateRating->execute([$newAvg, $hotel->id]);

    if ($hotel->id % 10 === 0 || $hotel->id <= 5) {
        echo "  Hotel #{$hotel->id} ({$hotel->name}): $ratingCount reviews, avg=$newAvg\n";
    }
}

$totalReviews = (int)$pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
echo "\n✅ Done! Total reviews inserted: $totalReviews\n";
echo "   Average per hotel: " . round($totalReviews / $totalHotels) . "\n";
