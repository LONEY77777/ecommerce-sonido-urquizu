<?php

namespace App\Http\Requests;

use App\Models\Cart;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    /**
     * A user may only touch rows in their own cart.
     */
    public function authorize(): bool
    {
        $cart = $this->route('cart');

        return $cart instanceof Cart && $cart->user_id === $this->user()?->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * Reject quantities beyond the product's available stock.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $product = $this->route('cart')->product;

                if ($this->integer('quantity') > $product->stock) {
                    $validator->errors()->add(
                        'quantity',
                        "Only {$product->stock} unit(s) of {$product->name} are in stock."
                    );
                }
            },
        ];
    }
}
