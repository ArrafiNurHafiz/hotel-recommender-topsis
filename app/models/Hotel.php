<?php
class Hotel extends Model
{
    protected static string $table = 'hotels';

    public static function getVerified(int $page = 1, int $perPage = 6, array $filters = []): object
    {
        $where = "h.status = 'verified'";
        $params = [];

        if (!empty($filters['search'])) {
            $where .= " AND (h.name LIKE ? OR h.address LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }
        if (!empty($filters['location'])) {
            $where .= " AND h.address LIKE ?";
            $params[] = "%{$filters['location']}%";
        }
        if (!empty($filters['price_min'])) {
            $where .= " AND h.price_start >= ?";
            $params[] = $filters['price_min'];
        }
        if (!empty($filters['price_max'])) {
            $where .= " AND h.price_start <= ?";
            $params[] = $filters['price_max'];
        }

        $total = (int) Database::query(
            "SELECT COUNT(*) FROM hotels h WHERE $where", $params
        )->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $items = Database::fetchAll(
            "SELECT h.*, u.name as admin_name
             FROM hotels h
             JOIN users u ON u.id = h.admin_id
             WHERE $where
             ORDER BY h.rating_avg DESC, h.price_start ASC
             LIMIT $perPage OFFSET $offset",
            $params
        );

        foreach ($items as $hotel) {
            $hotel->facilities = Database::fetchAll(
                "SELECT f.* FROM facilities f
                 JOIN hotel_facilities hf ON hf.facility_id = f.id
                 WHERE hf.hotel_id = ?",
                [$hotel->id]
            );
            $hotel->total_rooms = (int) Database::query(
                "SELECT COALESCE(SUM(total_room), 0) FROM rooms WHERE hotel_id = ?", [$hotel->id]
            )->fetchColumn();
            $hotel->occupancy = self::getOccupancy($hotel->id);
        }

        return (object)[
            'items'    => $items,
            'total'    => $total,
            'page'     => $page,
            'perPage'  => $perPage,
            'lastPage' => (int)ceil($total / $perPage),
        ];
    }

    public static function getOccupancy(int $hotelId): int
    {
        $row = Database::fetch(
            "SELECT COALESCE(SUM(total_room), 0) as total, COALESCE(SUM(occupied_room), 0) as occupied
             FROM rooms WHERE hotel_id = ?",
            [$hotelId]
        );
        if (!$row || $row->total == 0) return 0;
        return (int) round(($row->occupied / $row->total) * 100);
    }

    public static function allFacilities(): array
    {
        return Database::fetchAll("SELECT * FROM facilities ORDER BY name");
    }
}
