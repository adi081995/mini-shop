<?php

namespace App\Http\Requests;

use App\Models\Product;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'nullable',
            ],
            'quantity' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'category_id' => [
                'integer',
                'exists:categories,id',
                'nullable',
            ],
            'amount' => [
                'numeric',
                'required',
            ],
            'tag' => [
                'required',
                'array',
            ],
            'tag.*.id' => [
                'integer',
                'exists:tags,id',
            ],
        ];
    }
}
