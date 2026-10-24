<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
        'stock',
        'image',
    ];

    /**
     * Default values mirroring the database defaults.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'stock' => 0,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Match products whose name or category contains the given term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): Builder
    {
        return $query->when($term, function (Builder $query, string $term) {
            $query->where(function (Builder $query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('category', 'like', "%{$term}%");
            });
        });
    }

    /**
     * Only products that can currently be ordered.
     */
    #[Scope]
    protected function inStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Public URL for the product image, falling back to a bundled placeholder.
     */
    public function imageUrl(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        return asset('images/product-placeholder.svg');
    }

    /**
     * Truncated description used on product cards.
     */
    public function shortDescription(int $length = 100): string
    {
        return str($this->description)->limit($length)->toString();
    }
}
