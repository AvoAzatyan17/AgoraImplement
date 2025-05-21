<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgoraRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'channelName' => 'required|string',
            'uid' => 'required|integer',
        ];
    }
}
