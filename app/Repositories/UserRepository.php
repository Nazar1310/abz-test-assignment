<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{

    /**
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $page, int $perPage): LengthAwarePaginator
    {
        return User::query()->orderBy('id','DESC')->paginate(perPage: $perPage, page: $page);
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        /** @var User $user */
        $user = User::query()->create($data);
        return $user;
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        /** @var User|bool $user */
        $user = User::query()->where('email', $email)->first();
        return $user ?: null;
    }
}
