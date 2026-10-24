<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Any signed in user may check out their own cart.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Refuse checkout when the cart is empty or stock has run out meanwhile.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $cartItems = $this->user()->cartItems()->with('product')->get();

                if ($cartItems->isEmpty()) {
                    $validator->errors()->add('cart', 'Your cart is empty.');

                    return;
                }

                foreach ($cartItems as $item) {
                    if ($item->quantity > $item->product->stock) {
                        $validator->errors()->add(
                            'cart',
                            "{$item->product->name} only has {$item->product->stock} unit(s) left. Please update your cart."
                        );
                    }
                }
            },
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'customer_name' => 'full name',
        ];
    }
}
