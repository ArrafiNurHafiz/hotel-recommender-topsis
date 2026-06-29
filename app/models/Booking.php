<?php
class Booking extends Model
{
    protected static string $table = 'bookings';

    public static function userBookings(int $userId): array
    {
        return Database::fetchAll(
            "SELECT b.*, r.room_type, r.price, h.name as hotel_name, h.id as hotel_id
             FROM bookings b
             JOIN rooms r ON r.id = b.room_id
             JOIN hotels h ON h.id = r.hotel_id
             WHERE b.user_id = ?
             ORDER BY b.created_at DESC",
            [$userId]
        );
    }

    public static function hotelBookings(int $hotelId): array
    {
        return Database::fetchAll(
            "SELECT b.*, r.room_type, u.name as user_name
             FROM bookings b
             JOIN rooms r ON r.id = b.room_id
             JOIN users u ON u.id = b.user_id
             WHERE r.hotel_id = ?
             ORDER BY b.created_at DESC",
            [$hotelId]
        );
    }

    public static function checkAvailability(int $roomId, string $checkIn, string $checkOut, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM bookings
                WHERE room_id = ? AND status IN ('pending', 'confirmed')
                AND check_in < ? AND check_out > ?";
        $params = [$roomId, $checkOut, $checkIn];
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        return (int) Database::query($sql, $params)->fetchColumn() === 0;
    }
}
