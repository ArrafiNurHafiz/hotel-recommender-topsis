<?php
class AdminController extends Controller
{
    private function getHotel()
    {
        return Database::fetch("SELECT * FROM hotels WHERE admin_id = ?", [Auth::id()]);
    }

    public function dashboard(): void
    {
        $hotel = $this->getHotel();
        $stats = (object)['total_rooms' => 0, 'pending_bookings' => 0, 'total_reviews' => 0, 'occupancy' => 0];
        if ($hotel) {
            $stats->total_rooms = (int) Database::query("SELECT COUNT(*) FROM rooms WHERE hotel_id = ?", [$hotel->id])->fetchColumn();
            $stats->pending_bookings = (int) Database::query(
                "SELECT COUNT(*) FROM bookings b JOIN rooms r ON r.id = b.room_id WHERE r.hotel_id = ? AND b.status = 'pending'",
                [$hotel->id]
            )->fetchColumn();
            $stats->total_reviews = (int) Database::query("SELECT COUNT(*) FROM reviews WHERE hotel_id = ?", [$hotel->id])->fetchColumn();
            $stats->occupancy = Hotel::getOccupancy($hotel->id);
        }
        $this->view('admin/dashboard', [
            'title' => 'Dashboard Admin',
            'hotel' => $hotel,
            'stats' => $stats,
        ]);
    }

    public function editHotel(): void
    {
        $hotel = $this->getHotel();
        $facilities = Hotel::allFacilities();
        $selectedFacilities = [];
        if ($hotel) {
            $rows = Database::fetchAll("SELECT facility_id FROM hotel_facilities WHERE hotel_id = ?", [$hotel->id]);
            $selectedFacilities = array_map(fn($r) => $r->facility_id, $rows);
        }
        $this->view('admin/edit-hotel', [
            'title'              => 'Edit Profil Hotel',
            'hotel'              => $hotel,
            'facilities'         => $facilities,
            'selectedFacilities' => $selectedFacilities,
        ]);
    }

    public function updateHotel(): void
    {
        $hotel = $this->getHotel();
        $data = [
            'name'     => $_POST['name'] ?? '',
            'address'  => $_POST['address'] ?? '',
            'latitude'  => $_POST['latitude'] ?? 0,
            'longitude' => $_POST['longitude'] ?? 0,
            'price_start' => $_POST['price_start'] ?? 0,
        ];

        if ($hotel) {
            Hotel::update($hotel->id, $data);
            $hotelId = $hotel->id;
        } else {
            $data['admin_id'] = Auth::id();
            $data['status'] = 'pending';
            $hotelId = Hotel::create($data);
        }

        Database::delete('hotel_facilities', 'hotel_id = ?', [$hotelId]);
        $facilities = $_POST['facilities'] ?? [];
        foreach ((array)$facilities as $fId) {
            Database::insert('hotel_facilities', ['hotel_id' => $hotelId, 'facility_id' => (int)$fId]);
        }

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data hotel berhasil disimpan'];
        $this->redirect('/admin/dashboard');
    }

    public function rooms(): void
    {
        $hotel = $this->getHotel();
        $rooms = [];
        if ($hotel) {
            $rooms = Database::fetchAll("SELECT * FROM rooms WHERE hotel_id = ? ORDER BY created_at DESC", [$hotel->id]);
        }
        $this->view('admin/rooms', ['title' => 'Kelola Kamar', 'hotel' => $hotel, 'rooms' => $rooms]);
    }

    public function storeRoom(): void
    {
        $hotel = $this->getHotel();
        if (!$hotel) {
            $this->redirect('/admin/hotels/edit');
            return;
        }
        $data = [
            'hotel_id'     => $hotel->id,
            'room_type'    => $_POST['room_type'] ?? '',
            'price'        => $_POST['price'] ?? 0,
            'total_room'   => $_POST['total_room'] ?? 0,
            'occupied_room' => $_POST['occupied_room'] ?? 0,
            'is_active'    => (int)($_POST['is_active'] ?? 0),
            'status'       => ($_POST['occupied_room'] ?? 0) >= ($_POST['total_room'] ?? 0) ? 'full' : 'available',
        ];
        Room::create($data);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Kamar berhasil ditambahkan'];
        $this->redirect('/admin/rooms');
    }

    public function updateRoom(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $data = [
            'room_type'     => $_POST['room_type'] ?? '',
            'price'         => $_POST['price'] ?? 0,
            'total_room'    => $_POST['total_room'] ?? 0,
            'occupied_room' => $_POST['occupied_room'] ?? 0,
            'is_active'     => (int)($_POST['is_active'] ?? 0),
            'status'        => ($_POST['occupied_room'] ?? 0) >= ($_POST['total_room'] ?? 0) ? 'full' : 'available',
        ];
        Room::update($id, $data);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Kamar berhasil diupdate'];
        $this->redirect('/admin/rooms');
    }

    public function deleteRoom(): void
    {
        Room::delete((int)($_POST['id'] ?? 0));
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Kamar berhasil dihapus'];
        $this->redirect('/admin/rooms');
    }

    public function bookings(): void
    {
        $hotel = $this->getHotel();
        $bookings = [];
        if ($hotel) {
            $bookings = Booking::hotelBookings($hotel->id);
        }
        $this->view('admin/bookings', ['title' => 'Kelola Booking', 'bookings' => $bookings]);
    }

    public function confirmBooking(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        Booking::update($id, ['status' => 'confirmed']);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Booking dikonfirmasi'];
        $this->redirect('/admin/bookings');
    }

    public function cancelBooking(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        Booking::update($id, ['status' => 'cancelled']);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Booking dibatalkan'];
        $this->redirect('/admin/bookings');
    }

    public function checkoutBooking(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        Booking::update($id, ['status' => 'checked_out']);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Tamu sudah check-out'];
        $this->redirect('/admin/bookings');
    }

    public function reviews(): void
    {
        $hotel = $this->getHotel();
        $reviews = [];
        if ($hotel) {
            $reviews = Review::hotelReviews($hotel->id);
        }
        $this->view('admin/reviews', ['title' => 'Kelola Review', 'reviews' => $reviews]);
    }
}
