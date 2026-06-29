<?php
class Room extends Model
{
    protected static string $table = 'rooms';

    public static function getAvailable(int $hotelId): array
    {
        return Database::fetchAll(
            "SELECT * FROM rooms WHERE hotel_id = ? AND status = 'available'",
            [$hotelId]
        );
    }
}
