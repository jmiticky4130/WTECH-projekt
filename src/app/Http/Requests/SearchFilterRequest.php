<?php

namespace App\Http\Requests;

class SearchFilterRequest extends CategoryFilterRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'q' => ['sometimes', 'nullable', 'string', 'max:200'],
        ]);
    }
}
