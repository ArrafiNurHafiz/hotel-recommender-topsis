<?php
/**
 * Seeder file for hotel-recommender-topsis.
 * Reuses the $pdo connection setup in reset.php
 */

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
        'name'     => 'Admin Hotel Grand Hyatt',
        'email'    => 'admin.hyatt@hotel.com',
        'password' => password_hash('hyatt123', PASSWORD_BCRYPT),
        'phone'    => '081234567891',
        'role'     => 'admin_hotel',
        'active'   => 1
    ],
    [
        'name'     => 'Admin Hotel Ritz Carlton',
        'email'    => 'admin.ritz@hotel.com',
        'password' => password_hash('ritz123', PASSWORD_BCRYPT),
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

$adminHyattId = 2;
$adminRitzId = 3;

$facilities = ['WiFi Gratis', 'Kolam Renang', 'AC', 'Pusat Kebugaran', 'Restoran', 'Parkir Gratis', 'Spa'];
foreach ($facilities as $f) {
    $stmt = $pdo->prepare("INSERT INTO facilities (name) VALUES (?)");
    $stmt->execute([$f]);
}

$hotels = [
    [
        'name'        => 'Grand Hyatt Jakarta',
        'address'     => 'Jl. M.H. Thamrin No.Kav 28-30, Gondangdia, Jakarta Pusat',
        'latitude'    => -6.193125,
        'longitude'   => 106.821875,
        'price_start' => 1200000.00,
        'admin_id'    => $adminHyattId,
        'status'      => 'verified',
        'rating_avg'  => 4.5
    ],
    [
        'name'        => 'The Ritz-Carlton Jakarta',
        'address'     => 'Jl. DR. Ide Anak Agung Gde Agung Kav. E.1.1, Mega Kuningan, Jakarta Selatan',
        'latitude'    => -6.228125,
        'longitude'   => 106.827125,
        'price_start' => 2000000.00,
        'admin_id'    => $adminRitzId,
        'status'      => 'verified',
        'rating_avg'  => 4.8
    ]
];

foreach ($hotels as $h) {
    $stmt = $pdo->prepare("INSERT INTO hotels (name, address, latitude, longitude, price_start, admin_id, status, rating_avg) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$h['name'], $h['address'], $h['latitude'], $h['longitude'], $h['price_start'], $h['admin_id'], $h['status'], $h['rating_avg']]);
}

$hotelFacilities = [
    1 => [1, 2, 3, 5, 6], // Hyatt facilities
    2 => [1, 2, 3, 4, 5, 6, 7] // Ritz facilities
];

foreach ($hotelFacilities as $hotelId => $facs) {
    foreach ($facs as $facilityId) {
        $stmt = $pdo->prepare("INSERT INTO hotel_facilities (hotel_id, facility_id) VALUES (?, ?)");
        $stmt->execute([$hotelId, $facilityId]);
    }
}

$rooms = [
    [
        'hotel_id'      => 1,
        'room_type'     => 'Deluxe Room',
        'price'         => 1200000.00,
        'total_room'    => 20,
        'occupied_room' => 5,
        'is_active'     => 1,
        'status'        => 'available'
    ],
    [
        'hotel_id'      => 1,
        'room_type'     => 'Grand Suite',
        'price'         => 2500000.00,
        'total_room'    => 10,
        'occupied_room' => 2,
        'is_active'     => 1,
        'status'        => 'available'
    ],
    [
        'hotel_id'      => 2,
        'room_type'     => 'Grand Studio',
        'price'         => 2000000.00,
        'total_room'    => 15,
        'occupied_room' => 3,
        'is_active'     => 1,
        'status'        => 'available'
    ],
    [
        'hotel_id'      => 2,
        'room_type'     => 'Presidential Suite',
        'price'         => 5000000.00,
        'total_room'    => 5,
        'occupied_room' => 1,
        'is_active'     => 1,
        'status'        => 'available'
    ]
];

foreach ($rooms as $r) {
    $stmt = $pdo->prepare("INSERT INTO rooms (hotel_id, room_type, price, total_room, occupied_room, is_active, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$r['hotel_id'], $r['room_type'], $r['price'], $r['total_room'], $r['occupied_room'], $r['is_active'], $r['status']]);
}
