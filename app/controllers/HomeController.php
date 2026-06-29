<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $hotels = Hotel::getVerified(1, 3);
        $this->view('home/index', [
            'hotels' => $hotels->items,
            'title'  => 'Beranda',
        ]);
    }

    public function about(): void
    {
        $this->view('home/about', ['title' => 'Tentang Kami']);
    }

    public function howItWorks(): void
    {
        $this->view('home/how-it-works', ['title' => 'Cara Kerja']);
    }

    public function contact(): void
    {
        $this->view('home/contact', ['title' => 'Kontak']);
    }
}
