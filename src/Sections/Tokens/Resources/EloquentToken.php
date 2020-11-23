<?php

namespace AwemaPL\Auth\Sections\Tokens\Resources;

use AwemaPL\Auth\Sections\Options\Resources\EloquentOption;
use AwemaPL\Auth\Sections\Tokens\Models\PlainToken;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class EloquentToken extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new EloquentUser($this->tokenable),
            'name' => $this->name,
            'plain_token' => optional(PlainToken::where('token_id', $this->id)->first())->plain_token,
            'last_used_at' =>optional($this->last_used_at)->format('Y-m-d H:i:s'),
            'created_at' =>optional($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
