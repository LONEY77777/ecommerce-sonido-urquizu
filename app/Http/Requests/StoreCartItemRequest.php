<?php

namespace App\Http\Requests;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    /**
     * Any signed in user may fill their own cart.
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
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * Reject quantities that would push the cart past the product's stock.
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

                $product = Product::find($this->integer('product_id'));
                $alreadyInCart = Cart::where('user_id', $this->user()->id)
                    ->where('product_id', $this->integer('product_id'))
                    ->value('quantity') ?? 0;

                if ($product && $alreadyInCart + $this->integer('quantity') > $product->stock) {
                    $validator->errors()->add(
                        'quantity',
                        "Only {$product->stock} unit(s) of {$product->name} are in stock."
                    );
                }
            },
        ];
    }

    /**
     * Default the quantity so "Add to cart" buttons can omit it.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'quantity' => $this->input('quantity', 1),
        ]);
    }
}
