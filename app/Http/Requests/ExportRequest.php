<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_id' => 'required|exists:items,id',
            'quantity' => ['numeric', 'max:' . $this->getCurrentQuantity(), 'min:' . $this->getMinQuantity()],
            'customer_id' => 'required|exists:customers,id',

        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }

    //get current_quantity for the chosen item
    public function getCurrentQuantity(): ?int
    {

        $item = Item::find(request()->input('item_id'));
        if ($item) {
            return $item->current_quantity;
        }
        return null;
    }

    //get min_quantity for the chosen item
    public function getMinQuantity(): ?int
    {

        $item = Item::find(request()->input('item_id'));
        if ($item) {
            return $item->min_quantity;
        }
        return null;
    }
}
