<?php
/**
 * Seeder file for hotel-recommender-topsis.
 * Reuses the $pdo connection setup in reset.php
 */

// 1. Insert Users (Super Admin, Admins, and standard User)
$users = [
    [
        'name'     => 'Super Admin',
        'email'    => 'superadmin@hotel.com',
        'password' => password_hash('superadmin123', PASSWORD_BCRYPT),
        'phone'    => '081234567890',
        'role'     => 'super_admin',
        'active'   => 1
    ],
    [
        'name'     => 'Admin Hotel Group A',
        'email'    => 'admin.a@hotel.com',
        'password' => password_hash('admina123', PASSWORD_BCRYPT),
        'phone'    => '081234567891',
        'role'     => 'admin_hotel',
        'active'   => 1
    ],
    [
        'name'     => 'Admin Hotel Group B',
        'email'    => 'admin.b@hotel.com',
        'password' => password_hash('adminb123', PASSWORD_BCRYPT),
        'phone'    => '081234567892',
        'role'     => 'admin_hotel',
        'active'   => 1
    ],
    [
        'name'     => 'Arrafi Nur Hafiz',
        'email'    => 'user@hotel.com',
        'password' => password_hash('user123', PASSWORD_BCRYPT),
        'phone'    => '081234567893',
        'role'     => 'user',
        'active'   => 1
    ]
];

foreach ($users as $u) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, role, active) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$u['name'], $u['email'], $u['password'], $u['phone'], $u['role'], $u['active']]);
}

$adminAId = 2;
$adminBId = 3;

// 2. Insert Facilities
$facilities = [
    'WiFi Gratis', 'Kolam Renang', 'AC', 'Pusat Kebugaran', 
    'Restoran', 'Parkir Gratis', 'Spa', 'Layanan Kamar 24 Jam',
    'Pusat Bisnis', 'Penyewaan Sepeda'
];
foreach ($facilities as $f) {
    $stmt = $pdo->prepare("INSERT INTO facilities (name) VALUES (?)");
    $stmt->execute([$f]);
}

// 3. Define 20 Hotels with different images, prices, locations (around Jakarta/Bandung/Batam)
$hotelData = [
    [
        'name'        => 'Hotel Grand Hyatt Jakarta',
        'address'     => 'Jl. M.H. Thamrin No.Kav 28-30, Gondangdia, Jakarta Pusat',
        'latitude'    => -6.193125, 'longitude' => 106.821875,
        'price_start' => 2200000.00, 'admin_id' => $adminAId, 'status' => 'verified', 'rating_avg' => 4.8,
        'image'       => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80'
    ],
    [
        'name'        => 'The Ritz-Carlton Mega Kuningan',
        'address'     => 'Jl. DR. Ide Anak Agung Gde Agung Kav. E.1.1, Mega Kuningan, Jakarta Selatan',
        'latitude'    => -6.228125, 'longitude' => 106.827125,
        'price_start' => 2800000.00, 'admin_id' => $adminBId, 'status' => 'verified', 'rating_avg' => 4.9,
        'image'       => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=600&q=80'
    ],
    [
        'name'        => 'Hotel Mulia Senayan',
        'address'     => 'Jl. Asia Afrika, Senayan, Gelora, Jakarta Pusat',
        'latitude'    => -6.216335, 'longitude' => 106.797125,
        'price_start' => 2100000.00, 'admin_id' => $adminAId, 'status' => 'verified', 'rating_avg' => 4.7,
        'image'       => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&q=80'
    ],
    [
        'name'        => 'Pullman Jakarta Central Park',
        'address'     => 'Letjen S. Parman Kav. 21, Grogol Petamburan, Jakarta Barat',
        'latitude'    => -6.175125, 'longitude' => 106.790515,
        'price_start' => 1500000.00, 'admin_id' => $adminBId, 'status' => 'verified', 'rating_avg' => 4.6,
        'image'       => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=600&q=80'
    ],
    [
        'name'        => 'Shangri-La Hotel Jakarta',
        'address'     => 'Kota BNI, Jl. Jend. Sudirman Kav. 1, Tanah Abang, Jakarta Pusat',
        'latitude'    => -6.201235, 'longitude' => 106.819445,
        'price_start' => 1800000.00, 'admin_id' => $adminAId, 'status' => 'verified', 'rating_avg' => 4.7,
        'image'       => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=600&q=80'
    ],
    [
        'name'        => 'Padma Hotel Bandung',
        'address'     => 'Jl. Ranca Bentang No.56-58, Ciumbuleuit, Bandung',
        'latitude'    => -6.868735, 'longitude' => 107.614345,
        'price_start' => 1650000.00, 'admin_id' => $adminBId, 'status' => 'verified', 'rating_avg' => 4.8,
        'image'       => 'https://images.unsplash.com/photo-1455587734955-081b22074882?w=600&q=80'
    ],
    [
        'name'        => 'Trans Luxury Hotel Bandung',
        'address'     => 'Jl. Gatot Subroto No.289, Cibangkong, Batununggal, Bandung',
        'latitude'    => -6.926125, 'longitude' => 107.636445,
        'price_start' => 1900000.00, 'admin_id' => $adminAId, 'status' => 'verified', 'rating_avg' => 4.8,
        'image'       => 'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?w=600&q=80'
    ],
    [
        'name'        => 'Swiss-Belhotel Harbour Bay Batam',
        'address'     => 'Jalan Sungai Jodoh, Batu Ampar, Batam',
        'latitude'    => 1.151245, 'longitude' => 104.015245,
        'price_start' => 950000.00, 'admin_id' => $adminBId, 'status' => 'verified', 'rating_avg' => 4.4,
        'image'       => 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=600&q=80'
    ],
    [
        'name'        => 'Radisson Golf & Convention Center Batam',
        'address'     => 'Jl. Sudirman, Sukajadi, Batam',
        'latitude'    => 1.114125, 'longitude' => 104.032125,
        'price_start' => 1200000.00, 'admin_id' => $adminAId, 'status' => 'verified', 'rating_avg' => 4.5,
        'image'       => 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=600&q=80'
    ],
    [
        'name'        => 'Best Western Premier Panbil Batam',
        'address'     => 'Jl. Ahmad Yani, Muka Kuning, Batam',
        'latitude'    => 1.091125, 'longitude' => 104.048125,
        'price_start' => 880000.00, 'admin_id' => $adminBId, 'status' => 'verified', 'rating_avg' => 4.3,
        'image'       => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=600&q=80'
    ],
    [
        'name'        => 'Aston Kiara Hotel Jakarta',
        'address'     => 'Jl. Penerangan No. 2, Kebayoran Baru, Jakarta Selatan',
        'latitude'    => -6.251245, 'longitude' => 106.799125,
        'price_start' => 850000.00, 'admin_id' => $adminAId, 'status' => 'verified', 'rating_avg' => 4.2,
        'image'       => 'https://images.unsplash.com/photo-1606046604972-77cc76aee944?w=600&q=80'
    ],
    [
        'name'        => 'Harris Hotel Bandung',
        'address'     => 'Jl. Peta No.241, Pasir Koja, Bandung',
        'latitude'    => -6.937125, 'longitude' => 107.595125,
        'price_start' => 700000.00, 'admin_id' => $adminBId, 'status' => 'verified', 'rating_avg' => 4.3,
        'image'       => 'https://images.unsplash.com/photo-1517840901100-8179e982ca41?w=600&q=80'
    ],
    [
        'name'        => 'Artotel Thamrin Jakarta',
        'address'     => 'Jl. Sunda No.3, Gondangdia, Menteng, Jakarta Pusat',
        'latitude'    => -6.188735, 'longitude' => 106.824335,
        'price_start' => 750000.00, 'admin_id' => $adminAId, 'status' => 'verified', 'rating_avg' => 4.4,
        'image'       => 'https://images.unsplash.com/photo-1529290130-4ca3753253ae?w=600&q=80'
    ],
    [
        'name'        => 'The Gaia Hotel Bandung',
        'address'     => 'Jl. Dr. Setiabudi No.430, Ledeng, Cidadap, Bandung',
        'latitude'    => -6.832125, 'longitude' => 107.597125,
        'price_start' => 1700000.00, 'admin_id' => $adminBId, 'status' => 'verified', 'rating_avg' => 4.8,
        'image'       => 'https://images.unsplash.com/photo-1568495248636-6432b97bd949?w=600&q=80'
    ],
    [
        'name'        => 'The Sultan Hotel & Residence Jakarta',
        'address'     => 'Jl. Gatot Subroto, Gelora, Tanah Abang, Jakarta Pusat',
        'latitude'    => -6.218125, 'longitude' => 106.808125,
        'price_start' => 1100000.00, 'admin_id' => $adminAId, 'status' => 'verified', 'rating_avg' => 4.5,
        'image'       => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?w=600&q=80'
    ],
    [
        'name'        => 'Batam Marriott Hotel Harbour Bay',
        'address'     => 'Harbour Bay Downtown, Jl. Duyung, Sungai Jodoh, Batu Ampar, Batam',
        'latitude'    => 1.153445, 'longitude' => 104.014225,
        'price_start' => 2300000.00, 'admin_id' => $adminBId, 'status' => 'verified', 'rating_avg' => 4.9,
        'image'       => 'https://images.unsplash.com/photo-1521783988139-89397d761dce?w=600&q=80'
    ],
    [
        'name'        => 'DoubleTree by Hilton Jakarta',
        'address'     => 'Jl. Pegangsaan Timur No.17, Cikini, Menteng, Jakarta Pusat',
        'latitude'    => -6.200115, 'longitude' => 106.843115,
        'price_start' => 1350000.00, 'admin_id' => $adminAId, 'status' => 'verified', 'rating_avg' => 4.6,
        'image'       => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=600&q=80'
    ],
    [
        'name'        => 'de Braga by ARTOTEL Bandung',
        'address'     => 'Jl. Braga No.10, Braga, Sumur Bandung, Bandung',
        'latitude'    => -6.917125, 'longitude' => 107.610125,
        'price_start' => 600000.00, 'admin_id' => $adminBId, 'status' => 'verified', 'rating_avg' => 4.4,
        'image'       => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=600&q=80'
    ],
    [
        'name'        => 'Mercure Batam Centre',
        'address'     => 'Jl. Raden Patah No.12, Baloi Indah, Lubuk Baja, Batam',
        'latitude'    => 1.127125, 'longitude' => 104.017125,
        'price_start' => 780000.00, 'admin_id' => $adminAId, 'status' => 'verified', 'rating_avg' => 4.3,
        'image'       => 'https://images.unsplash.com/photo-1554009975-d74653b849f1?w=600&q=80'
    ],
    [
        'name'        => 'Hotel Indonesia Kempinski Jakarta',
        'address'     => 'Jl. M.H. Thamrin No.1, Menteng, Jakarta Pusat',
        'latitude'    => -6.195125, 'longitude' => 106.822875,
        'price_start' => 2500000.00, 'admin_id' => $adminBId, 'status' => 'verified', 'rating_avg' => 4.8,
        'image'       => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600&q=80'
    ]
];

// 4. Insert Hotels
foreach ($hotelData as $h) {
    $stmt = $pdo->prepare("INSERT INTO hotels (name, address, latitude, longitude, price_start, admin_id, status, rating_avg, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$h['name'], $h['address'], $h['latitude'], $h['longitude'], $h['price_start'], $h['admin_id'], $h['status'], $h['rating_avg'], $h['image']]);
}

// 5. Connect Facilities to Hotels (randomly assign 4-7 facilities to each hotel)
for ($hotelId = 1; $hotelId <= 20; $hotelId++) {
    $numFacilities = rand(4, 8);
    $assigned = array_rand(range(1, 10), $numFacilities);
    if (!is_array($assigned)) {
        $assigned = [$assigned];
    }
    foreach ($assigned as $facIdx) {
        $facilityId = $facIdx + 1; // 1-indexed
        $stmt = $pdo->prepare("INSERT IGNORE INTO hotel_facilities (hotel_id, facility_id) VALUES (?, ?)");
        $stmt->execute([$hotelId, $facilityId]);
    }
}

// 6. Insert Rooms for all 20 hotels
for ($hotelId = 1; $hotelId <= 20; $hotelId++) {
    $priceBase = $hotelData[$hotelId - 1]['price_start'];
    
    // Standard Room
    $stmt = $pdo->prepare("INSERT INTO rooms (hotel_id, room_type, price, total_room, occupied_room, is_active, status) VALUES (?, 'Standard Room', ?, ?, ?, 1, 'available')");
    $stmt->execute([$hotelId, $priceBase, 30, rand(5, 25)]);
    
    // Deluxe Room
    $stmt = $pdo->prepare("INSERT INTO rooms (hotel_id, room_type, price, total_room, occupied_room, is_active, status) VALUES (?, 'Deluxe Room', ?, ?, ?, 1, 'available')");
    $stmt->execute([$hotelId, $priceBase * 1.3, 15, rand(2, 12)]);
    
    // Suite Room
    $stmt = $pdo->prepare("INSERT INTO rooms (hotel_id, room_type, price, total_room, occupied_room, is_active, status) VALUES (?, 'Executive Suite', ?, ?, ?, 1, 'available')");
    $stmt->execute([$hotelId, $priceBase * 1.8, 5, rand(0, 4)]);
}
