<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    /**
     * @param User $user
     */
    public function __construct(private User $user)
    {
        parent::__construct($user);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'position' => $this->user->position->name,
            'position_id' => $this->user->position_id,
            'photo' => $this->user->photo_path ? asset($this->user->photo_path) : asset("img/default_avatar.png"),
            'registration_timestamp' => $this->user->created_at->timestamp,
        ];
    }
}
