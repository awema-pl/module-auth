<?php

namespace AwemaPL\Auth\Sections\Tokens\Models;

use Illuminate\Database\Eloquent\Model;
use AwemaPL\Auth\Sections\Tokens\Models\Contracts\PlainToken as PlainTokenContract;

class PlainToken extends Model implements PlainTokenContract
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token_id', 'plain_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'token_id' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('awemapl-auth.tables.plain_tokens');
    }

    public function getPlainTokenAttribute($value)
    {
        if ($value){
            return decrypt($value);
        } else {
            $value;
        }
    }
}
