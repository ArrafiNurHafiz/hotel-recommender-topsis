<?php
class SuperAdminController extends Controller
{
    public function dashboard(): void
    {
        $stats = (object)[
            'total_users'      => User::count(),
            'total_hotels'     => Hotel::count(),
            'pending_hotels'   => (int) Database::query("SELECT COUNT(*) FROM hotels WHERE status = 'pending'")->fetchColumn(),
            'total_bookings'   => Booking::count(),
            'total_reviews'    => (int) Database::query("SELECT COUNT(*) FROM reviews")->fetchColumn(),
            'verified_hotels'  => (int) Database::query("SELECT COUNT(*) FROM hotels WHERE status = 'verified'")->fetchColumn(),
        ];
        $this->view('super-admin/dashboard', ['title' => 'Dashboard Super Admin', 'stats' => $stats]);
    }

    public function users(): void
    {
        $users = Database::fetchAll("SELECT * FROM users ORDER BY created_at DESC");
        $this->view('super-admin/users', ['title' => 'Kelola User', 'users' => $users]);
    }

    public function toggleUser(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $user = User::find($id);
        if ($user && $user->role !== 'super_admin') {
            $newStatus = $user->active ? 0 : 1;
            Database::update('users', ['active' => $newStatus], 'id = :id', ['id' => $id]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Status user berhasil diubah'];
        }
        $this->redirect('/super-admin/users');
    }

    public function hotels(): void
    {
        $status = $_GET['status'] ?? 'pending';
        $hotels = Database::fetchAll(
            "SELECT h.*, u.name as admin_name FROM hotels h JOIN users u ON u.id = h.admin_id WHERE h.status = ? ORDER BY h.created_at DESC",
            [$status]
        );
        $this->view('super-admin/hotels', ['title' => 'Verifikasi Hotel', 'hotels' => $hotels, 'currentStatus' => $status]);
    }

    public function verifyHotel(): void
    {
        $id     = (int)($_POST['id'] ?? 0);
        $action = $_POST['action'] ?? '';
        $status = $action === 'approve' ? 'verified' : 'rejected';
        Hotel::update($id, ['status' => $status]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Hotel ' . ($action === 'approve' ? 'disetujui' : 'ditolak')];
        $this->redirect('/super-admin/hotels');
    }

    public function monitoring(): void
    {
        $monthlyBookings = Database::fetchAll("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total
            FROM bookings GROUP BY month ORDER BY month DESC LIMIT 12
        ");
        $topHotels = Database::fetchAll("
            SELECT h.name, COUNT(b.id) as total_bookings, AVG(rv.rating) as avg_rating
            FROM bookings b
            JOIN rooms r ON r.id = b.room_id
            JOIN hotels h ON h.id = r.hotel_id
            LEFT JOIN reviews rv ON rv.hotel_id = h.id
            GROUP BY h.id, h.name
            ORDER BY total_bookings DESC LIMIT 5
        ");
        $this->view('super-admin/monitoring', [
            'title'                 => 'Monitoring',
            'monthlyBookings'       => $monthlyBookings,
            'topHotels'             => $topHotels,
        ]);
    }
}
