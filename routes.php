<?php
// ========== GUEST ==========
Router::get('/', [HomeController::class, 'index']);
Router::get('/about', [HomeController::class, 'about']);
Router::get('/how-it-works', [HomeController::class, 'howItWorks']);
Router::get('/contact', [HomeController::class, 'contact']);
Router::get('/login', [AuthController::class, 'loginForm'], 'GuestMiddleware');
Router::post('/login', [AuthController::class, 'login']);
Router::get('/register', [AuthController::class, 'registerForm'], 'GuestMiddleware');
Router::post('/register', [AuthController::class, 'register']);

// ========== USER (login required) ==========
Router::get('/hotels', [HotelController::class, 'index']);
Router::get('/hotels/{id}', [HotelController::class, 'show']);
Router::get('/recommendations', [RecommendationController::class, 'index']);
Router::get('/my-bookings', [BookingController::class, 'myBookings'], 'UserMiddleware');
Router::get('/booking/{room_id}', [BookingController::class, 'create'], 'UserMiddleware');
Router::post('/booking', [BookingController::class, 'store'], 'UserMiddleware');
Router::post('/review', [ReviewController::class, 'store'], 'UserMiddleware');
Router::post('/logout', [AuthController::class, 'logout']);

// ========== ADMIN HOTEL ==========
Router::get('/admin/login', [AuthController::class, 'adminLoginForm'], 'GuestMiddleware');
Router::post('/admin/login', [AuthController::class, 'adminLogin']);
Router::get('/admin/dashboard', [AdminController::class, 'dashboard'], 'AdminHotelMiddleware');
Router::get('/admin/hotels/edit', [AdminController::class, 'editHotel'], 'AdminHotelMiddleware');
Router::post('/admin/hotels/update', [AdminController::class, 'updateHotel'], 'AdminHotelMiddleware');
Router::get('/admin/rooms', [AdminController::class, 'rooms'], 'AdminHotelMiddleware');
Router::post('/admin/rooms/store', [AdminController::class, 'storeRoom'], 'AdminHotelMiddleware');
Router::post('/admin/rooms/update', [AdminController::class, 'updateRoom'], 'AdminHotelMiddleware');
Router::post('/admin/rooms/delete', [AdminController::class, 'deleteRoom'], 'AdminHotelMiddleware');
Router::get('/admin/bookings', [AdminController::class, 'bookings'], 'AdminHotelMiddleware');
Router::post('/admin/bookings/confirm', [AdminController::class, 'confirmBooking'], 'AdminHotelMiddleware');
Router::post('/admin/bookings/cancel', [AdminController::class, 'cancelBooking'], 'AdminHotelMiddleware');
Router::post('/admin/bookings/checkout', [AdminController::class, 'checkoutBooking'], 'AdminHotelMiddleware');
Router::get('/admin/reviews', [AdminController::class, 'reviews'], 'AdminHotelMiddleware');

// ========== SUPER ADMIN ==========
Router::get('/super-admin/login', [AuthController::class, 'superAdminLoginForm'], 'GuestMiddleware');
Router::post('/super-admin/login', [AuthController::class, 'superAdminLogin']);
Router::get('/super-admin/dashboard', [SuperAdminController::class, 'dashboard'], 'SuperAdminMiddleware');
Router::get('/super-admin/users', [SuperAdminController::class, 'users'], 'SuperAdminMiddleware');
Router::post('/super-admin/users/toggle', [SuperAdminController::class, 'toggleUser'], 'SuperAdminMiddleware');
Router::get('/super-admin/hotels', [SuperAdminController::class, 'hotels'], 'SuperAdminMiddleware');
Router::post('/super-admin/hotels/verify', [SuperAdminController::class, 'verifyHotel'], 'SuperAdminMiddleware');
Router::get('/super-admin/monitoring', [SuperAdminController::class, 'monitoring'], 'SuperAdminMiddleware');
