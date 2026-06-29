<?php
class Review extends Model
{
    protected static string $table = 'reviews';

    public static function hotelReviews(int $hotelId): array
    {
        return Database::fetchAll(
            "SELECT rv.*, u.name as user_name
             FROM reviews rv
             JOIN users u ON u.id = rv.user_id
             WHERE rv.hotel_id = ?
             ORDER BY rv.created_at DESC",
            [$hotelId]
        );
    }

    public static function updateHotelRating(int $hotelId): void
    {
        $result = Database::fetch(
            "SELECT AVG(rating) as avg_rating FROM reviews WHERE hotel_id = ?",
            [$hotelId]
        );
        $rating = $result->avg_rating ? round($result->avg_rating, 1) : 0;
        Database::update('hotels', ['rating_avg' => $rating], 'id = :id', ['id' => $hotelId]);
    }
}
