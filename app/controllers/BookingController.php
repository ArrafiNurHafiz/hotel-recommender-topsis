<?php
class BookingController extends Controller
{
    public function myBookings(): void
    {
        $bookings = Booking::userBookings(Auth::id());
        $this->view('bookings/index', [
            'title'    => 'Riwayat Booking Saya',
            'bookings' => $bookings,
        ]);
    }

    public function create(string $roomId): void
    {
        $room = Room::find((int)$roomId);
        if (!$room || $room->status !== 'available') {
            $this->redirect('/hotels');
            return;
        }
        $hotel = Hotel::find($room->hotel_id);
        $this->view('bookings/create', [
            'title' => 'Booking Kamar',
            'room'  => $room,
            'hotel' => $hotel,
        ]);
    }

    public function store(): void
    {
        $roomId  = (int)($_POST['room_id'] ?? 0);
        $checkIn = $_POST['check_in'] ?? '';
        $checkOut = $_POST['check_out'] ?? '';

        $room = Room::find($roomId);
        if (!$room) {
            $this->redirect('/hotels');
            return;
        }

        $errors = [];

        if (strtotime($checkIn) < strtotime(date('Y-m-d'))) {
            $errors[] = 'Check-in tidak boleh sebelum hari ini';
        }
        if (strtotime($checkOut) <= strtotime($checkIn)) {
            $errors[] = 'Check-out harus setelah check-in';
        }

        if (!Booking::checkAvailability($roomId, $checkIn, $checkOut)) {
            $errors[] = 'Kamar sudah dibooking di tanggal tersebut';
        }

        if ($errors) {
            $this->view('bookings/create', [
                'title'  => 'Booking Kamar',
                'room'   => $room,
                'hotel'  => Hotel::find($room->hotel_id),
                'errors' => $errors,
                'old'    => $_POST,
            ]);
            return;
        }

        $days = max(1, (strtotime($checkOut) - strtotime($checkIn)) / 86400);
        $totalPrice = $room->price * $days;

        Booking::create([
            'user_id'     => Auth::id(),
            'room_id'     => $roomId,
            'check_in'    => $checkIn,
            'check_out'   => $checkOut,
            'total_price' => $totalPrice,
            'status'      => 'pending',
        ]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Booking berhasil dibuat, tunggu konfirmasi admin.'];
        $this->redirect('/my-bookings');
    }
}
