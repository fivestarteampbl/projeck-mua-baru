{{-- resources/views/emails/booking-dibuat.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Diterima</title>
</head>
<body style="margin:0;padding:0;background-color:#FAF8F6;font-family:Helvetica,Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FAF8F6;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width:560px;background-color:#FFFFFF;border-radius:16px;border:1px solid #EDE9E6;overflow:hidden;">
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 8px;font-size:12px;letter-spacing:0.2em;text-transform:uppercase;color:#9B8C81;">Booking</p>
                            <h1 style="margin:0 0 16px;font-size:24px;color:#241F1C;">Terima kasih, {{ $booking->nama_pelanggan }}!</h1>
                            <p style="margin:0 0 24px;font-size:14px;color:#5E5048;line-height:1.6;">
                                Pemesanan Anda telah kami terima. Berikut ringkasannya:
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #EDE9E6;border-radius:12px;font-size:14px;">
                                <tr>
                                    <td style="padding:12px 16px;border-bottom:1px solid #EDE9E6;color:#7A6B60;">Kode Booking</td>
                                    <td style="padding:12px 16px;border-bottom:1px solid #EDE9E6;color:#241F1C;font-weight:bold;">{{ $booking->kode_booking }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;border-bottom:1px solid #EDE9E6;color:#7A6B60;">Paket</td>
                                    <td style="padding:12px 16px;border-bottom:1px solid #EDE9E6;color:#241F1C;">{{ $booking->pricelist?->nama_pricelist }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;border-bottom:1px solid #EDE9E6;color:#7A6B60;">Tanggal &amp; Waktu</td>
                                    <td style="padding:12px 16px;border-bottom:1px solid #EDE9E6;color:#241F1C;">{{ $booking->tanggal_acara->format('d M Y') }} &middot; {{ $booking->waktu_acara }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;border-bottom:1px solid #EDE9E6;color:#7A6B60;">Lokasi</td>
                                    <td style="padding:12px 16px;border-bottom:1px solid #EDE9E6;color:#241F1C;">{{ $booking->lokasi_acara }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;border-bottom:1px solid #EDE9E6;color:#7A6B60;">Total Harga</td>
                                    <td style="padding:12px 16px;border-bottom:1px solid #EDE9E6;color:#241F1C;">Rp {{ number_format((float) $booking->total_harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;color:#7A6B60;">DP (30%)</td>
                                    <td style="padding:12px 16px;color:#241F1C;">Rp {{ number_format((float) $booking->dp, 0, ',', '.') }}</td>
                                </tr>
                            </table>

                            <p style="margin:24px 0 0;font-size:13px;color:#7A6B60;line-height:1.6;">
                                Kami akan menghubungi Anda melalui WhatsApp untuk konfirmasi lebih lanjut.
                                Anda juga dapat mengecek status booking kapan saja melalui halaman cek booking di website kami.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
