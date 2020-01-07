<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
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
            'company_token' => 'required|string',
            'priority' => 'required|in:minor,major,central',
            'device' => 'required|string',
            'os' => 'required|string',
            'memory' => 'required|integer',
            'storage' => 'required|integer'
        ];
    }
}
