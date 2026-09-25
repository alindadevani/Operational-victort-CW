<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    /**
     * Menampilkan halaman QR Code absensi kantor.
     * QR ini hanya satu untuk seluruh kantor, mengarah ke route "attendance.create".
     * Admin bisa mencetak/menempel QR ini di area absensi kantor.
     */
    public function index(): View
    {
        $url = route('attendance.create');

        // Generate QR Code dalam format SVG (tidak butuh ekstensi GD/Imagick tambahan)
        $qrCode = QrCode::size(280)->generate($url);

        return view('attendance.qrcode', compact('qrCode', 'url'));
    }
}
