<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'phone',
        'address',
        'notes',
        'total_amount',
        'status',
    ];

    /**
     * Default values mirroring the database defaults.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => OrderStatus::Pending->value,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'status' => OrderStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Only orders whose revenue should be counted on the dashboard.
     */
    #[Scope]
    protected function revenueGenerating(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::Completed);
    }

    /**
     * Match orders by order number, customer name or phone.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): Builder
    {
        return $query->when($term, function (Builder $query, string $term) {
            $query->where(function (Builder $query) use ($term) {
                $query->where('order_number', 'like', "%{$term}%")
                    ->orWhere('customer_name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%");
            });
        });
    }

    /**
     * Generate a unique, human friendly order number.
     */
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'SH-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (static::where('order_number', $number)->exists());

        return $number;
    }
}
