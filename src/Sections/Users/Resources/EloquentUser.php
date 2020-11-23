<?php

namespace AwemaPL\Auth\Sections\Users\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class EloquentUser extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' =>optional($this->email_verified_at)->format('Y-m-d H:i:s'),
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
