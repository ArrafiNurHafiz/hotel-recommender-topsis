<?php
/**
 * Seed 120 additional hotels across Indonesia
 * Run: php database/seed_hotels.php
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

// Get existing admin IDs
$admins = $pdo->query("SELECT id FROM users WHERE role IN ('admin_hotel','super_admin') ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);
$adminAId = $admins[0] ?? 1;
$adminBId = $admins[1] ?? 2;

// Unsplash hotel image pool (40 different images, will cycle)
$images = [
    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80',
    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=600&q=80',
    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&q=80',
    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=600&q=80',
    'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=600&q=80',
    'https://images.unsplash.com/photo-1455587734955-081b22074882?w=600&q=80',
    'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?w=600&q=80',
    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=600&q=80',
    'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=600&q=80',
    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=600&q=80',
    'https://images.unsplash.com/photo-1606046604972-77cc76aee944?w=600&q=80',
    'https://images.unsplash.com/photo-1517840901100-8179e982ca41?w=600&q=80',
    'https://images.unsplash.com/photo-1529290130-4ca3753253ae?w=600&q=80',
    'https://images.unsplash.com/photo-1568495248636-6432b97bd949?w=600&q=80',
    'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?w=600&q=80',
    'https://images.unsplash.com/photo-1521783988139-89397d761dce?w=600&q=80',
    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=600&q=80',
    'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=600&q=80',
    'https://images.unsplash.com/photo-1554009975-d74653b849f1?w=600&q=80',
    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600&q=80',
    'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?w=600&q=80',
    'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=600&q=80',
    'https://images.unsplash.com/photo-1459767129954-1b1c1f9b9ace?w=600&q=80',
    'https://images.unsplash.com/photo-1519449556851-5720b33024e7?w=600&q=80',
    'https://images.unsplash.com/photo-1530521954074-e64f6810b32d?w=600&q=80',
    'https://images.unsplash.com/photo-1544124499-58912cbddaad?w=600&q=80',
    'https://images.unsplash.com/photo-1549294413-26f195200c16?w=600&q=80',
    'https://images.unsplash.com/photo-1561501900-3701fa6a0864?w=600&q=80',
    'https://images.unsplash.com/photo-1563911302283-d2bc129e7570?w=600&q=80',
    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=600&q=80',
    'https://images.unsplash.com/photo-1586611292717-f828b167408c?w=600&q=80',
    'https://images.unsplash.com/photo-1587213811864-c02b4ec39f2c?w=600&q=80',
    'https://images.unsplash.com/photo-1600011689032-8b628b8a8747?w=600&q=80',
    'https://images.unsplash.com/photo-1601701119533-fde77d02ebe4?w=600&q=80',
    'https://images.unsplash.com/photo-1602002418082-a4443e081dd1?w=600&q=80',
    'https://images.unsplash.com/photo-1603298933070-75b20ec6c9b8?w=600&q=80',
    'https://images.unsplash.com/photo-1605346434674-a440ca4dc4c0?w=600&q=80',
    'https://images.unsplash.com/photo-1607619056574-7b8d3ee536b2?w=600&q=80',
    'https://images.unsplash.com/photo-1613553507747-5f8d62ad5904?w=600&q=80',
    'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=600&q=80',
    'https://images.unsplash.com/photo-1625244724120-1fd1d34d00f6?w=600&q=80',
    'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&q=80',
    'https://images.unsplash.com/photo-1634650504527-e0a26db1aa5b?w=600&q=80',
    'https://images.unsplash.com/photo-1637335855994-7fc89e548bc9?w=600&q=80',
    'https://images.unsplash.com/photo-1643810978765-4c449a7a0f18?w=600&q=80',
    'https://images.unsplash.com/photo-1648491411651-9a2b96c40ed5?w=600&q=80',
    'https://images.unsplash.com/photo-1653924826757-a4dd3d5bbe60?w=600&q=80',
    'https://images.unsplash.com/photo-1659352405029-72e7e8e3d1fd?w=600&q=80',
    'https://images.unsplash.com/photo-1662761823218-d1dd8b8fb39e?w=600&q=80',
    'https://images.unsplash.com/photo-1673459271929-a3cb8a2f6da7?w=600&q=80',
];

$hotels = [
    // ===== BALI (15) =====
    ['name'=>'Four Seasons Resort Bali at Jimbaran Bay','address'=>'Jl. Bukit Permai, Jimbaran, Bali','lat'=>-8.7942,'lng'=>115.1686,'price'=>4500000,'admin'=>$adminAId,'rating'=>5.0],
    ['name'=>'The St. Regis Bali Resort','address'=>'Kawasan Pariwisata Nusa Dua, Lot S6, Bali','lat'=>-8.7999,'lng'=>115.2339,'price'=>5000000,'admin'=>$adminBId,'rating'=>4.9],
    ['name'=>'Bulgari Resort Bali','address'=>'Jl. Goa Lempeh, Uluwatu, Bali','lat'=>-8.8266,'lng'=>115.0887,'price'=>6000000,'admin'=>$adminAId,'rating'=>5.0],
    ['name'=>'Alila Villas Uluwatu','address'=>'Jl. Belimbing Sari, Pecatu, Bali','lat'=>-8.8290,'lng'=>115.0915,'price'=>4200000,'admin'=>$adminBId,'rating'=>4.8],
    ['name'=>'COMO Shambhala Estate Bali','address'=>'Banjar Begawan, Melinggih Kelod, Ubud, Bali','lat'=>-8.4905,'lng'=>115.2935,'price'=>3800000,'admin'=>$adminAId,'rating'=>4.9],
    ['name'=>'Mandapa, a Ritz-Carlton Reserve','address'=>'Jl. Kedewatan, Ubud, Bali','lat'=>-8.4793,'lng'=>115.2576,'price'=>7000000,'admin'=>$adminBId,'rating'=>5.0],
    ['name'=>'Park Hyatt Bali','address'=>'Kawasan Pariwisata Nusa Dua, Bali','lat'=>-8.8003,'lng'=>115.2300,'price'=>3500000,'admin'=>$adminAId,'rating'=>4.8],
    ['name'=>'Seminyak Beach Resort & Spa','address'=>'Jl. Camplung Tanduk, Seminyak, Bali','lat'=>-8.6872,'lng'=>115.1570,'price'=>1800000,'admin'=>$adminBId,'rating'=>4.4],
    ['name'=>'Kuta Seaview Boutique Resort','address'=>'Jl. Pantai Kuta, Kuta, Bali','lat'=>-8.7186,'lng'=>115.1686,'price'=>900000,'admin'=>$adminAId,'rating'=>4.1],
    ['name'=>'Sanur Paradise Plaza Hotel','address'=>'Jl. Hang Tuah No.46, Sanur, Denpasar','lat'=>-8.6874,'lng'=>115.2621,'price'=>1100000,'admin'=>$adminBId,'rating'=>4.3],
    ['name'=>'Grand Mirage Resort & Thalasso Bali','address'=>'Jl. Pratama No.74, Nusa Dua, Bali','lat'=>-8.7995,'lng'=>115.2312,'price'=>1600000,'admin'=>$adminAId,'rating'=>4.5],
    ['name'=>'Komaneka at Bisma Ubud','address'=>'Jl. Bisma, Ubud, Bali','lat'=>-8.5111,'lng'=>115.2624,'price'=>2800000,'admin'=>$adminBId,'rating'=>4.7],
    ['name'=>'Ayodya Resort Bali','address'=>'Jl. Pantai Mengiat, Nusa Dua, Bali','lat'=>-8.7978,'lng'=>115.2298,'price'=>1400000,'admin'=>$adminAId,'rating'=>4.4],
    ['name'=>'The Laguna, a Luxury Collection Resort','address'=>'Kawasan Pariwisata Nusa Dua, Bali','lat'=>-8.7991,'lng'=>115.2325,'price'=>2200000,'admin'=>$adminBId,'rating'=>4.7],
    ['name'=>'Potato Head Suites & Studios Bali','address'=>'Jl. Petitenget No.51B, Seminyak, Bali','lat'=>-8.6828,'lng'=>115.1549,'price'=>2500000,'admin'=>$adminAId,'rating'=>4.6],

    // ===== SURABAYA (10) =====
    ['name'=>'Shangri-La Surabaya','address'=>'Jl. Mayjend Yono Suwoyo, Surabaya','lat'=>-7.2902,'lng'=>112.7368,'price'=>1800000,'admin'=>$adminBId,'rating'=>4.7],
    ['name'=>'Gran Melia Hotel Surabaya','address'=>'Jl. Jend. Basuki Rachmat No.56-58, Surabaya','lat'=>-7.2632,'lng'=>112.7527,'price'=>1500000,'admin'=>$adminAId,'rating'=>4.5],
    ['name'=>'Westin Surabaya','address'=>'Pakuwon Mall, Lt. 8-16, Surabaya','lat'=>-7.2892,'lng'=>112.7350,'price'=>1600000,'admin'=>$adminBId,'rating'=>4.6],
    ['name'=>'JW Marriott Hotel Surabaya','address'=>'Jl. Embong Malang No.85-89, Surabaya','lat'=>-7.2654,'lng'=>112.7481,'price'=>1700000,'admin'=>$adminAId,'rating'=>4.7],
    ['name'=>'Pullman Surabaya City Centre','address'=>'Jl. Rajawali No.11-15, Surabaya','lat'=>-7.2441,'lng'=>112.7347,'price'=>1200000,'admin'=>$adminBId,'rating'=>4.4],
    ['name'=>'Sheraton Surabaya Hotel & Towers','address'=>'Jl. Embong Malang No.25-31, Surabaya','lat'=>-7.2650,'lng'=>112.7490,'price'=>1350000,'admin'=>$adminAId,'rating'=>4.5],
    ['name'=>'Novotel Surabaya Hotel & Suites','address'=>'Jl. Ngagel No.173-175, Surabaya','lat'=>-7.2874,'lng'=>112.7532,'price'=>850000,'admin'=>$adminBId,'rating'=>4.2],
    ['name'=>'Bumi Surabaya City Resort','address'=>'Jl. Jend. Basuki Rachmat No.106-128, Surabaya','lat'=>-7.2649,'lng'=>112.7510,'price'=>1100000,'admin'=>$adminAId,'rating'=>4.4],
    ['name'=>'Swiss-Belinn Tunjungan Surabaya','address'=>'Jl. Tunjungan No.102, Surabaya','lat'=>-7.2612,'lng'=>112.7377,'price'=>650000,'admin'=>$adminBId,'rating'=>4.0],
    ['name'=>'Aston Surabaya Hotel','address'=>'Jl. Stasiun Kota No.1, Surabaya','lat'=>-7.2496,'lng'=>112.7341,'price'=>580000,'admin'=>$adminAId,'rating'=>3.9],

    // ===== YOGYAKARTA (10) =====
    ['name'=>'Royal Ambarrukmo Hotel Yogyakarta','address'=>'Jl. Laksda Adisucipto No.81, Yogyakarta','lat'=>-7.7884,'lng'=>110.4042,'price'=>1200000,'admin'=>$adminBId,'rating'=>4.5],
    ['name'=>'Grand Aston Yogyakarta','address'=>'Jl. Ring Road Utara, Condong Catur, Yogyakarta','lat'=>-7.7458,'lng'=>110.4020,'price'=>900000,'admin'=>$adminAId,'rating'=>4.3],
    ['name'=>'Hyatt Regency Yogyakarta','address'=>'Jl. Palagan Tentara Pelajar, Yogyakarta','lat'=>-7.7338,'lng'=>110.3844,'price'=>1500000,'admin'=>$adminBId,'rating'=>4.6],
    ['name'=>'Tentrem Hotel Yogyakarta','address'=>'Jl. AM Sangaji No.72A, Jetis, Yogyakarta','lat'=>-7.7930,'lng'=>110.3600,'price'=>1800000,'admin'=>$adminAId,'rating'=>4.7],
    ['name'=>'Harper Malioboro Yogyakarta','address'=>'Jl. Letjen Suprapto No.8, Yogyakarta','lat'=>-7.7960,'lng'=>110.3669,'price'=>700000,'admin'=>$adminBId,'rating'=>4.2],
    ['name'=>'Novotel Yogyakarta','address'=>'Jl. Jend. Sudirman No.89, Yogyakarta','lat'=>-7.8013,'lng'=>110.3712,'price'=>900000,'admin'=>$adminAId,'rating'=>4.3],
    ['name'=>'Phoenix Hotel Yogyakarta','address'=>'Jl. Jend. Sudirman No.9, Yogyakarta','lat'=>-7.7997,'lng'=>110.3681,'price'=>800000,'admin'=>$adminBId,'rating'=>4.1],
    ['name'=>'Melia Purosani Hotel Yogyakarta','address'=>'Jl. Suryotomo No.31, Yogyakarta','lat'=>-7.7969,'lng'=>110.3679,'price'=>850000,'admin'=>$adminAId,'rating'=>4.2],
    ['name'=>'Alana Yogyakarta Hotel & Convention Center','address'=>'Jl. Palagan Tentara Pelajar Km.7, Yogyakarta','lat'=>-7.7311,'lng'=>110.3849,'price'=>1100000,'admin'=>$adminBId,'rating'=>4.4],
    ['name'=>'The Westlake Resort Yogyakarta','address'=>'Jl. Wijilan No.48, Yogyakarta','lat'=>-7.7963,'lng'=>110.3645,'price'=>600000,'admin'=>$adminAId,'rating'=>4.0],

    // ===== MEDAN (8) =====
    ['name'=>'JW Marriott Hotel Medan','address'=>'Jl. Putri Hijau No.10, Medan','lat'=>3.5905,'lng'=>98.6721,'price'=>1600000,'admin'=>$adminBId,'rating'=>4.7],
    ['name'=>'Grand Mercure Medan Angkasa','address'=>'Jl. Sutomo No.1, Medan','lat'=>3.5850,'lng'=>98.6712,'price'=>900000,'admin'=>$adminAId,'rating'=>4.3],
    ['name'=>'Santika Premiere Dyandra Hotel & Convention Medan','address'=>'Jl. Kapten Maulana Lubis No.7, Medan','lat'=>3.5878,'lng'=>98.6834,'price'=>1100000,'admin'=>$adminBId,'rating'=>4.4],
    ['name'=>'Aryaduta Medan','address'=>'Jl. Kapten Maulana Lubis No.8, Medan','lat'=>3.5882,'lng'=>98.6829,'price'=>950000,'admin'=>$adminAId,'rating'=>4.2],
    ['name'=>'Garuda Plaza Hotel Medan','address'=>'Jl. Sisingamangaraja No.18, Medan','lat'=>3.5725,'lng'=>98.6783,'price'=>650000,'admin'=>$adminBId,'rating'=>3.9],
    ['name'=>'Grand Aston City Hall Medan','address'=>'Jl. Balai Kota No.1, Medan','lat'=>3.5876,'lng'=>98.6785,'price'=>850000,'admin'=>$adminAId,'rating'=>4.2],
    ['name'=>'The Residence Hotel Medan','address'=>'Jl. Gatot Subroto No.395, Medan','lat'=>3.5754,'lng'=>98.6845,'price'=>700000,'admin'=>$adminBId,'rating'=>4.0],
    ['name'=>'Emerald Garden International Hotel Medan','address'=>'Jl. Kolonel Laut Yos Sudarso No.1, Medan','lat'=>3.6082,'lng'=>98.6721,'price'=>580000,'admin'=>$adminAId,'rating'=>3.8],

    // ===== MAKASSAR (8) =====
    ['name'=>'Four Points by Sheraton Makassar','address'=>'Jl. Andi Djemma No.130, Makassar','lat'=>-5.1477,'lng'=>119.4227,'price'=>1200000,'admin'=>$adminBId,'rating'=>4.5],
    ['name'=>'Claro Hotel Makassar','address'=>'Jl. Andi Djemma No.130, Makassar','lat'=>-5.1481,'lng'=>119.4230,'price'=>1000000,'admin'=>$adminAId,'rating'=>4.4],
    ['name'=>'Aryaduta Makassar','address'=>'Jl. Somba Opu No.297, Makassar','lat'=>-5.1412,'lng'=>119.4148,'price'=>900000,'admin'=>$adminBId,'rating'=>4.3],
    ['name'=>'Swiss-Belhotel Makassar','address'=>'Jl. Puri Taman Sari, Makassar','lat'=>-5.1399,'lng'=>119.4256,'price'=>750000,'admin'=>$adminAId,'rating'=>4.1],
    ['name'=>'Hotel Gammara Makassar','address'=>'Jl. Metro Tanjung Bunga, Makassar','lat'=>-5.1699,'lng'=>119.4003,'price'=>650000,'admin'=>$adminBId,'rating'=>4.0],
    ['name'=>'Ramada Encore Makassar','address'=>'Jl. Metro Tanjung Bunga, Makassar','lat'=>-5.1688,'lng'=>119.4012,'price'=>700000,'admin'=>$adminAId,'rating'=>4.1],
    ['name'=>'Harper Perintis Makassar','address'=>'Jl. Urip Sumoharjo No.30, Makassar','lat'=>-5.1467,'lng'=>119.4345,'price'=>600000,'admin'=>$adminBId,'rating'=>3.9],
    ['name'=>'Novotel Makassar Grand Shayla','address'=>'Jl. Chairil Anwar No.28, Makassar','lat'=>-5.1458,'lng'=>119.4154,'price'=>850000,'admin'=>$adminAId,'rating'=>4.2],

    // ===== SEMARANG (8) =====
    ['name'=>'PO Hotel Semarang','address'=>'Jl. Pemuda No.118, Semarang','lat'=>-6.9839,'lng'=>110.4152,'price'=>1200000,'admin'=>$adminBId,'rating'=>4.6],
    ['name'=>'Grand Candi Hotel Semarang','address'=>'Jl. Sisingamangaraja No.16, Semarang','lat'=>-7.0020,'lng'=>110.4236,'price'=>950000,'admin'=>$adminAId,'rating'=>4.3],
    ['name'=>'Santika Premiere Hotel Semarang','address'=>'Jl. A. Yani No.189, Semarang','lat'=>-6.9838,'lng'=>110.4247,'price'=>800000,'admin'=>$adminBId,'rating'=>4.2],
    ['name'=>'Crowne Plaza Semarang','address'=>'Jl. Pemuda No.118, Semarang','lat'=>-6.9841,'lng'=>110.4152,'price'=>1100000,'admin'=>$adminAId,'rating'=>4.4],
    ['name'=>'Aston Inn Pandanaran Semarang','address'=>'Jl. Pandanaran No.58, Semarang','lat'=>-6.9889,'lng'=>110.4157,'price'=>600000,'admin'=>$adminBId,'rating'=>4.0],
    ['name'=>'Louis Kienne Hotel Simpang Lima','address'=>'Jl. Ahmad Dahlan No.8, Semarang','lat'=>-6.9935,'lng'=>110.4175,'price'=>700000,'admin'=>$adminAId,'rating'=>4.1],
    ['name'=>'Horison Nindya Semarang','address'=>'Jl. Brigjend Katamso No.1, Semarang','lat'=>-6.9877,'lng'=>110.4095,'price'=>550000,'admin'=>$adminBId,'rating'=>3.9],
    ['name'=>'Quest Hotel Semarang','address'=>'Jl. Gajah Mada No.138, Semarang','lat'=>-6.9932,'lng'=>110.4117,'price'=>500000,'admin'=>$adminAId,'rating'=>3.8],

    // ===== MALANG (7) =====
    ['name'=>'Hotel Tugu Malang','address'=>'Jl. Tugu No.3, Malang','lat'=>-7.9780,'lng'=>112.6209,'price'=>2800000,'admin'=>$adminBId,'rating'=>4.8],
    ['name'=>'Atria Hotel Malang','address'=>'Jl. Letjen Sutoyo No.79, Malang','lat'=>-7.9840,'lng'=>112.6363,'price'=>900000,'admin'=>$adminAId,'rating'=>4.3],
    ['name'=>'Aria Gajayana Hotel Malang','address'=>'Jl. Dr. Cipto No.17, Malang','lat'=>-7.9815,'lng'=>112.6321,'price'=>750000,'admin'=>$adminBId,'rating'=>4.2],
    ['name'=>'Swiss-Belinn Malang','address'=>'Jl. Letjen Sutoyo No.22, Malang','lat'=>-7.9843,'lng'=>112.6379,'price'=>650000,'admin'=>$adminAId,'rating'=>4.0],
    ['name'=>'Grand Mercure Malang Mirama','address'=>'Jl. Kahuripan No.9, Malang','lat'=>-7.9759,'lng'=>112.6266,'price'=>800000,'admin'=>$adminBId,'rating'=>4.2],
    ['name'=>'Jiwa Jawa Resort Bromo','address'=>'Desa Wonokitri, Pasuruan, Malang','lat'=>-7.9422,'lng'=>112.9504,'price'=>1200000,'admin'=>$adminAId,'rating'=>4.5],
    ['name'=>'Lava View Lodge Bromo','address'=>'Cemoro Lawang, Probolinggo','lat'=>-7.9276,'lng'=>112.9530,'price'=>600000,'admin'=>$adminBId,'rating'=>4.1],

    // ===== LOMBOK (8) =====
    ['name'=>'The Oberoi Beach Resort Lombok','address'=>'Medana Beach, Tanjung, Lombok','lat'=>-8.3572,'lng'=>116.0686,'price'=>3500000,'admin'=>$adminAId,'rating'=>4.9],
    ['name'=>'Katamaran Resort & Spa Lombok','address'=>'Senggigi, Lombok','lat'=>-8.5142,'lng'=>116.0590,'price'=>1200000,'admin'=>$adminBId,'rating'=>4.4],
    ['name'=>'Puri Mas Boutique Resort Lombok','address'=>'Jl. Raya Senggigi Km.8, Lombok','lat'=>-8.5062,'lng'=>116.0617,'price'=>900000,'admin'=>$adminAId,'rating'=>4.3],
    ['name'=>'Villa Lumbung Hotel & Restaurant','address'=>'Jl. Raya Senggigi Km.10, Lombok','lat'=>-8.5198,'lng'=>116.0583,'price'=>700000,'admin'=>$adminBId,'rating'=>4.1],
    ['name'=>'The Harmony Lombok Resort','address'=>'Jl. Pantai Sekotong, Gerung, Lombok','lat'=>-8.7162,'lng'=>116.0504,'price'=>500000,'admin'=>$adminAId,'rating'=>4.0],
    ['name'=>'Qunci Villas Hotel Lombok','address'=>'Jl. Raya Mangsit, Senggigi, Lombok','lat'=>-8.5027,'lng'=>116.0627,'price'=>1100000,'admin'=>$adminBId,'rating'=>4.5],
    ['name'=>'Sudamala Suites & Villas Lombok','address'=>'Jl. Raya Senggigi, Lombok','lat'=>-8.5090,'lng'=>116.0612,'price'=>1400000,'admin'=>$adminAId,'rating'=>4.6],
    ['name'=>'Sheraton Senggigi Beach Resort','address'=>'Jl. Raya Senggigi Km.8, Lombok','lat'=>-8.5145,'lng'=>116.0589,'price'=>1800000,'admin'=>$adminBId,'rating'=>4.7],

    // ===== PALEMBANG (5) =====
    ['name'=>'Hotel Aryaduta Palembang','address'=>'Jl. POM IX, Palembang','lat'=>-2.9952,'lng'=>104.7625,'price'=>1100000,'admin'=>$adminAId,'rating'=>4.4],
    ['name'=>'Swiss-Belhotel Internasional Palembang','address'=>'Jl. Rajawali, Palembang','lat'=>-2.9843,'lng'=>104.7614,'price'=>850000,'admin'=>$adminBId,'rating'=>4.2],
    ['name'=>'Hotel Santika Premiere Palembang','address'=>'Jl. Kapten A. Rivai No.23, Palembang','lat'=>-2.9881,'lng'=>104.7559,'price'=>750000,'admin'=>$adminAId,'rating'=>4.1],
    ['name'=>'Novotel Palembang Hotel & Residence','address'=>'Jl. R. Sukamto No.8A, Palembang','lat'=>-2.9842,'lng'=>104.7504,'price'=>900000,'admin'=>$adminBId,'rating'=>4.3],
    ['name'=>'Grand Inna Daira Palembang','address'=>'Jl. Jend. Sudirman No.81, Palembang','lat'=>-2.9879,'lng'=>104.7512,'price'=>650000,'admin'=>$adminAId,'rating'=>3.9],

    // ===== MANADO (5) =====
    ['name'=>'Sutan Raja Hotel & Convention Centre Manado','address'=>'Jl. Piere Tendean Boulevard, Manado','lat'=>1.4847,'lng'=>124.8399,'price'=>800000,'admin'=>$adminBId,'rating'=>4.2],
    ['name'=>'Swiss-Belhotel Maleosan Manado','address'=>'Jl. Piere Tendean No.28, Manado','lat'=>1.4854,'lng'=>124.8421,'price'=>700000,'admin'=>$adminAId,'rating'=>4.1],
    ['name'=>'Aryaduta Manado','address'=>'Jl. Piere Tendean No.1, Manado','lat'=>1.4862,'lng'=>124.8391,'price'=>900000,'admin'=>$adminBId,'rating'=>4.3],
    ['name'=>'Sintesa Peninsula Hotel Manado','address'=>'Jl. Jend. Sudirman, Manado','lat'=>1.4831,'lng'=>124.8368,'price'=>600000,'admin'=>$adminAId,'rating'=>4.0],
    ['name'=>'Aston Manado Hotel','address'=>'Jl. Piere Tendean No.30, Manado','lat'=>1.4858,'lng'=>124.8412,'price'=>650000,'admin'=>$adminBId,'rating'=>4.0],

    // ===== BALIKPAPAN (5) =====
    ['name'=>'Novotel Balikpapan','address'=>'Jl. Brigjen Ery Suparjan No.2, Balikpapan','lat'=>-1.2624,'lng'=>116.8319,'price'=>900000,'admin'=>$adminAId,'rating'=>4.3],
    ['name'=>'Grand Senyiur Hotel Balikpapan','address'=>'Jl. A. Yani No.33, Balikpapan','lat'=>-1.2697,'lng'=>116.8289,'price'=>700000,'admin'=>$adminBId,'rating'=>4.1],
    ['name'=>'Swiss-Belhotel Borneo Balikpapan','address'=>'Jl. Mayjen Sutoyo No.40, Balikpapan','lat'=>-1.2681,'lng'=>116.8302,'price'=>800000,'admin'=>$adminAId,'rating'=>4.2],
    ['name'=>'Hotel Gran Senyiur Balikpapan','address'=>'Jl. A. Yani No.33, Balikpapan','lat'=>-1.2700,'lng'=>116.8290,'price'=>650000,'admin'=>$adminBId,'rating'=>4.0],
    ['name'=>'Aston Balikpapan Hotel','address'=>'Jl. Jend. Sudirman No.50, Balikpapan','lat'=>-1.2654,'lng'=>116.8278,'price'=>550000,'admin'=>$adminAId,'rating'=>3.9],

    // ===== PONTIANAK (4) =====
    ['name'=>'Mercure Pontianak City Centre','address'=>'Jl. Gajah Mada No.1, Pontianak','lat'=>0.0263,'lng'=>109.3332,'price'=>750000,'admin'=>$adminBId,'rating'=>4.2],
    ['name'=>'Hotel Santika Pontianak','address'=>'Jl. Diponegoro No.46, Pontianak','lat'=>0.0278,'lng'=>109.3354,'price'=>600000,'admin'=>$adminAId,'rating'=>4.0],
    ['name'=>'Aston Pontianak Hotel & Convention Center','address'=>'Jl. Rahadi Usman No.2, Pontianak','lat'=>0.0265,'lng'=>109.3325,'price'=>700000,'admin'=>$adminBId,'rating'=>4.1],
    ['name'=>'Swiss-Belhotel Borneo Pontianak','address'=>'Jl. A. Yani No.21, Pontianak','lat'=>0.0241,'lng'=>109.3276,'price'=>550000,'admin'=>$adminAId,'rating'=>3.9],

    // ===== PEKANBARU (4) =====
    ['name'=>'Novotel Pekanbaru','address'=>'Jl. Riau No.7, Pekanbaru','lat'=>0.5313,'lng'=>101.4490,'price'=>800000,'admin'=>$adminBId,'rating'=>4.2],
    ['name'=>'Hotel Pangeran Pekanbaru','address'=>'Jl. Jend. Sudirman No.371, Pekanbaru','lat'=>0.5364,'lng'=>101.4495,'price'=>600000,'admin'=>$adminAId,'rating'=>4.0],
    ['name'=>'Grand Central Hotel Pekanbaru','address'=>'Jl. Jend. Sudirman No.9, Pekanbaru','lat'=>0.5372,'lng'=>101.4472,'price'=>700000,'admin'=>$adminBId,'rating'=>4.1],
    ['name'=>'Swiss-Belinn Pekanbaru','address'=>'Jl. Harapan Raya, Pekanbaru','lat'=>0.5098,'lng'=>101.4323,'price'=>550000,'admin'=>$adminAId,'rating'=>3.9],

    // ===== SOLO (SURAKARTA) (5) =====
    ['name'=>'Hotel Alila Solo','address'=>'Jl. Slamet Riyadi No.562, Solo','lat'=>-7.5620,'lng'=>110.7918,'price'=>900000,'admin'=>$adminBId,'rating'=>4.4],
    ['name'=>'Lorin Solo Hotel','address'=>'Jl. Adi Sucipto No.47, Solo','lat'=>-7.5577,'lng'=>110.8262,'price'=>750000,'admin'=>$adminAId,'rating'=>4.2],
    ['name'=>'Novotel Solo','address'=>'Jl. Slamet Riyadi No.272, Solo','lat'=>-7.5656,'lng'=>110.8091,'price'=>850000,'admin'=>$adminBId,'rating'=>4.3],
    ['name'=>'Hotel Sahid Jaya Solo','address'=>'Jl. Gajah Mada No.82, Solo','lat'=>-7.5599,'lng'=>110.8117,'price'=>650000,'admin'=>$adminAId,'rating'=>4.0],
    ['name'=>'Megaland Hotel Solo','address'=>'Jl. DR. Radjiman No.364, Solo','lat'=>-7.5659,'lng'=>110.8034,'price'=>550000,'admin'=>$adminBId,'rating'=>3.9],

    // ===== JAKARTA TAMBAHAN (10) =====
    ['name'=>'The Raffles Jakarta','address'=>'Ciputra World 1, Jl. Prof. Dr. Satrio Kav.3-5, Jakarta','lat'=>-6.2235,'lng'=>106.8218,'price'=>3800000,'admin'=>$adminAId,'rating'=>4.9],
    ['name'=>'Mandarin Oriental Jakarta','address'=>'Jl. M.H. Thamrin, Jakarta Pusat','lat'=>-6.1934,'lng'=>106.8224,'price'=>3200000,'admin'=>$adminBId,'rating'=>4.8],
    ['name'=>'The Westin Jakarta','address'=>'Jl. H.R. Rasuna Said Kav.C-22A, Jakarta','lat'=>-6.2279,'lng'=>106.8339,'price'=>2100000,'admin'=>$adminAId,'rating'=>4.7],
    ['name'=>'InterContinental Jakarta Pondok Indah','address'=>'Jl. Metro Pondok Indah No.4, Jakarta','lat'=>-6.2773,'lng'=>106.7878,'price'=>1900000,'admin'=>$adminBId,'rating'=>4.6],
    ['name'=>'Fairmont Jakarta','address'=>'Jl. Asia Afrika No.8, Gelora, Jakarta','lat'=>-6.2163,'lng'=>106.7985,'price'=>2500000,'admin'=>$adminAId,'rating'=>4.8],
    ['name'=>'Grand Hyatt Jakarta','address'=>'Jl. M.H. Thamrin Kav 28-30, Jakarta','lat'=>-6.1934,'lng'=>106.8218,'price'=>2200000,'admin'=>$adminBId,'rating'=>4.7],
    ['name'=>'Aloft Jakarta SCBD','address'=>'Jl. Senopati No.101-103, Jakarta','lat'=>-6.2249,'lng'=>106.8057,'price'=>1100000,'admin'=>$adminAId,'rating'=>4.4],
    ['name'=>'Hotel Monopoli Jakarta','address'=>'Jl. Kebon Jeruk Raya No.110, Jakarta','lat'=>-6.1978,'lng'=>106.7841,'price'=>650000,'admin'=>$adminBId,'rating'=>4.0],
    ['name'=>'favehotel Braga Bandung','address'=>'Jl. Braga No.99, Bandung','lat'=>-6.9176,'lng'=>107.6096,'price'=>380000,'admin'=>$adminAId,'rating'=>3.8],
    ['name'=>'Hotel Majapahit Surabaya','address'=>'Jl. Tunjungan No.65, Surabaya','lat'=>-7.2586,'lng'=>112.7378,'price'=>1400000,'admin'=>$adminBId,'rating'=>4.6],

    // ===== BANDUNG TAMBAHAN (5) =====
    ['name'=>'Savoy Homann Bidakara Hotel Bandung','address'=>'Jl. Asia Afrika No.112, Bandung','lat'=>-6.9222,'lng'=>107.6065,'price'=>800000,'admin'=>$adminAId,'rating'=>4.2],
    ['name'=>'Grand Hotel Preanger Bandung','address'=>'Jl. Asia Afrika No.81, Bandung','lat'=>-6.9218,'lng'=>107.6072,'price'=>750000,'admin'=>$adminBId,'rating'=>4.1],
    ['name'=>'Intercontinental Bandung Dago Pakar','address'=>'Jl. Ir. H. Djuanda No.29, Bandung','lat'=>-6.8812,'lng'=>107.6139,'price'=>1800000,'admin'=>$adminAId,'rating'=>4.7],
    ['name'=>'Crowne Plaza Bandung','address'=>'Jl. Lembong No.19, Bandung','lat'=>-6.9168,'lng'=>107.6120,'price'=>1000000,'admin'=>$adminBId,'rating'=>4.3],
    ['name'=>'Hilton Bandung','address'=>'Jl. HOS Cokroaminoto No.41A, Bandung','lat'=>-6.9151,'lng'=>107.6098,'price'=>1200000,'admin'=>$adminAId,'rating'=>4.5],

    // ===== BATAM TAMBAHAN (5) =====
    ['name'=>'Harmoni One Convention Hotel & Service Apartments','address'=>'Jl. Imam Bonjol, Nagoya, Batam','lat'=>1.1250,'lng'=>104.0238,'price'=>680000,'admin'=>$adminBId,'rating'=>4.1],
    ['name'=>'Golden View Hotel Batam','address'=>'Jl. Yos Sudarso, Batu Ampar, Batam','lat'=>1.1512,'lng'=>104.0148,'price'=>550000,'admin'=>$adminAId,'rating'=>3.9],
    ['name'=>'Premier Inn Batam','address'=>'Jl. Engku Putri No.1, Batam Centre','lat'=>1.1310,'lng'=>104.0550,'price'=>480000,'admin'=>$adminBId,'rating'=>3.8],
    ['name'=>'G Hotel Batam','address'=>'Komplek Ruko, Jl. Duyung, Batam','lat'=>1.1524,'lng'=>104.0154,'price'=>420000,'admin'=>$adminAId,'rating'=>3.7],
    ['name'=>'B One Hotel Batam','address'=>'Jl. Imam Bonjol, Nagoya, Batam','lat'=>1.1239,'lng'=>104.0249,'price'=>380000,'admin'=>$adminBId,'rating'=>3.6],

    // ===== RAJA AMPAT & LABUAN BAJO (5) =====
    ['name'=>'Papua Paradise Eco Resort','address'=>'Pulau Batanta, Raja Ampat, Papua Barat','lat'=>-0.7152,'lng'=>130.5226,'price'=>3500000,'admin'=>$adminAId,'rating'=>4.9],
    ['name'=>'Misool Eco Resort','address'=>'Pulau Misool, Raja Ampat, Papua Barat','lat'=>-2.0816,'lng'=>130.1748,'price'=>8500000,'admin'=>$adminBId,'rating'=>5.0],
    ['name'=>'Komodo Resort Labuan Bajo','address'=>'Pulau Sebayur, Labuan Bajo, NTT','lat'=>-8.4596,'lng'=>119.8931,'price'=>2800000,'admin'=>$adminAId,'rating'=>4.7],
    ['name'=>'Bintang Flores Hotel','address'=>'Jl. Soekarno Hatta, Labuan Bajo, NTT','lat'=>-8.4970,'lng'=>119.8858,'price'=>1100000,'admin'=>$adminBId,'rating'=>4.3],
    ['name'=>'The Jayakarta Lombok Beach Resort & Spa','address'=>'Jl. Raya Senggigi Km.4, Lombok','lat'=>-8.5189,'lng'=>116.0569,'price'=>900000,'admin'=>$adminAId,'rating'=>4.2],
];

// Get total existing hotels count for reference
$existingCount = (int)$pdo->query("SELECT COUNT(*) FROM hotels")->fetchColumn();
echo "Existing hotels: $existingCount\n";

// Get total existing facilities
$facCount = (int)$pdo->query("SELECT COUNT(*) FROM facilities")->fetchColumn();
echo "Existing facilities: $facCount\n";

// Insert hotels
$insertStmt = $pdo->prepare("
    INSERT INTO hotels (name, address, latitude, longitude, price_start, admin_id, status, rating_avg, image)
    VALUES (?, ?, ?, ?, ?, ?, 'verified', ?, ?)
");

$roomStmt = $pdo->prepare("
    INSERT INTO rooms (hotel_id, room_type, price, total_room, occupied_room, is_active, status)
    VALUES (?, ?, ?, ?, ?, 1, 'available')
");

$facStmt = $pdo->prepare("
    INSERT IGNORE INTO hotel_facilities (hotel_id, facility_id) VALUES (?, ?)
");

$totalImages = count($images);

foreach ($hotels as $idx => $h) {
    $image = $images[$idx % $totalImages];

    $insertStmt->execute([
        $h['name'],
        $h['address'],
        $h['lat'],
        $h['lng'],
        $h['price'],
        $h['admin'],
        $h['rating'],
        $image,
    ]);
    $hotelId = (int)$pdo->lastInsertId();

    // Add rooms
    $base = $h['price'];
    $roomStmt->execute([$hotelId, 'Standard Room',   $base,       30, rand(5, 25)]);
    $roomStmt->execute([$hotelId, 'Deluxe Room',     $base * 1.3, 15, rand(2, 12)]);
    $roomStmt->execute([$hotelId, 'Executive Suite', $base * 1.8,  5, rand(0, 4)]);

    // Assign 4-8 random facilities
    if ($facCount > 0) {
        $numFac = rand(4, min(8, $facCount));
        $facIds = range(1, $facCount);
        shuffle($facIds);
        $selected = array_slice($facIds, 0, $numFac);
        foreach ($selected as $fid) {
            $facStmt->execute([$hotelId, $fid]);
        }
    }

    echo "Inserted hotel #" . ($existingCount + $idx + 1) . ": {$h['name']}\n";
}

$newCount = (int)$pdo->query("SELECT COUNT(*) FROM hotels")->fetchColumn();
echo "\n✅ Done! Total hotels in database: $newCount\n";
