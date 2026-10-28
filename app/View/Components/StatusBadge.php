<?php

namespace App\View\Components;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\TestimoniStatus;
use Illuminate\View\Component;
use Illuminate\View\View;

class StatusBadge extends Component
{
    public string $warna;

    public string $label;

    public function __construct(BookingStatus|PaymentStatus|TestimoniStatus $status)
    {
        $this->label = $status->label();
        $this->warna = $this->tentukanWarna($status);
    }

    private function tentukanWarna(BookingStatus|PaymentStatus|TestimoniStatus $status): string
    {
        return match (true) {
            $status instanceof BookingStatus => match ($status) {
                BookingStatus::Pending => 'yellow',
                BookingStatus::Konfirmasi => 'blue',
                BookingStatus::Selesai => 'green',
                BookingStatus::Dibatalkan,
                BookingStatus::Ditolak => 'red',
            },
            $status instanceof PaymentStatus => match ($status) {
                PaymentStatus::BelumDp => 'red',
                PaymentStatus::Dp => 'blue',
                PaymentStatus::Lunas => 'green',
            },
            $status instanceof TestimoniStatus => match ($status) {
                TestimoniStatus::Pending => 'yellow',
                TestimoniStatus::Ditampilkan => 'green',
                TestimoniStatus::Disembunyikan => 'gray',
            },
        };
    }

    public function render(): View
    {
        return view('components.status-badge');
    }
}
