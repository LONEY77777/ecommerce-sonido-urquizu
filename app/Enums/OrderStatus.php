<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    /**
     * Human readable label for display in views.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    /**
     * Tailwind utility classes used to render the status badge.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-100 text-amber-800 ring-amber-600/20',
            self::Processing => 'bg-sky-100 text-sky-800 ring-sky-600/20',
            self::Completed => 'bg-emerald-100 text-emerald-800 ring-emerald-600/20',
            self::Cancelled => 'bg-rose-100 text-rose-800 ring-rose-600/20',
        };
    }

    /**
     * Revenue is only recognised for orders that were seen through to the end.
     */
    public function countsAsRevenue(): bool
    {
        return $this === self::Completed;
    }

    /**
     * All backing values, handy for validation rules and filters.
     *
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
