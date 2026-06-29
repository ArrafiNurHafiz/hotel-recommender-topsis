<?php
class HotelController extends Controller
{
    public function index(): void
    {
        $filters = [
            'search'     => $_GET['search'] ?? '',
            'location'   => $_GET['location'] ?? '',
            'price_min'  => $_GET['price_min'] ?? '',
            'price_max'  => $_GET['price_max'] ?? '',
        ];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = Hotel::getVerified($page, 6, $filters);
        $facilities = Hotel::allFacilities();

        $this->view('hotels/index', [
            'title'      => 'Daftar Hotel',
            'hotels'     => $result->items,
            'pagination' => $result,
            'filters'    => $filters,
            'facilities' => $facilities,
        ]);
    }

    public function show(string $id): void
    {
        $hotel = Hotel::find((int)$id);
        if (!$hotel || $hotel->status !== 'verified') {
            $this->redirect('/hotels');
            return;
        }

        $hotel->facilities = Database::fetchAll(
            "SELECT f.* FROM facilities f
             JOIN hotel_facilities hf ON hf.facility_id = f.id
             WHERE hf.hotel_id = ?",
            [$hotel->id]
        );
        $rooms  = Room::getAvailable($hotel->id);
        $reviews = Review::hotelReviews($hotel->id);
        $occupancy = Hotel::getOccupancy($hotel->id);

        $this->view('hotels/show', [
            'title'     => $hotel->name,
            'hotel'     => $hotel,
            'rooms'     => $rooms,
            'reviews'   => $reviews,
            'occupancy' => $occupancy,
        ]);
    }
}
