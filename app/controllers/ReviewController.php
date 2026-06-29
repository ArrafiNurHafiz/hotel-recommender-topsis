<?php
class ReviewController extends Controller
{
    public function store(): void
    {
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $rating    = (int)($_POST['rating'] ?? 0);
        $comment   = trim($_POST['comment'] ?? '');

        $booking = Booking::find($bookingId);
        if (!$booking || $booking->user_id !== Auth::id() || $booking->status !== 'checked_out') {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Tidak dapat memberikan review'];
            $this->back();
            return;
        }

        $room = Room::find($booking->room_id);
        $existing = Database::fetch(
            "SELECT id FROM reviews WHERE user_id = ? AND hotel_id = ?",
            [Auth::id(), $room->hotel_id]
        );
        if ($existing) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Anda sudah memberikan review untuk hotel ini'];
            $this->back();
            return;
        }

        if ($rating < 1 || $rating > 5) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Rating harus 1-5'];
            $this->back();
            return;
        }

        Review::create([
            'user_id'  => Auth::id(),
            'hotel_id' => $room->hotel_id,
            'rating'   => $rating,
            'comment'  => $comment,
        ]);

        Review::updateHotelRating($room->hotel_id);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Review berhasil dikirim'];
        $this->redirect('/my-bookings');
    }
}
