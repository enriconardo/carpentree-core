<?php

namespace Carpentree\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class MetaFieldResource extends JsonResource
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
            'type' => 'meta',
            'id' => $this->id,
            'locale' => App::getLocale(),

            'attributes' => [
                'key' => $this->key,
                'value' => $this->value,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ]

        ];
    }
}
