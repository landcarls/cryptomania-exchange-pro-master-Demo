<?php

namespace App\Http\Requests\User\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWalletBalanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|between:0.00000001,99999999999.99999999'
        ];
    }
}
