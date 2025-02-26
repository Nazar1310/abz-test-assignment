<?php

namespace App\Repositories;

use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class UserRepository implements UserRepositoryInterface
{

    /**
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $page, int $perPage): LengthAwarePaginator
    {
        return User::with('position')
            ->orderBy('id','DESC')
            ->paginate(perPage: $perPage, page: $page);
    }

    /**
     * @return Collection
     */
    public function getAllPositions(): Collection
    {
        $cacheKey = 'positions_data';
        $cacheTTL = now()->addDays(7);

        return Cache::remember($cacheKey, $cacheTTL, function () {
            return Position::query()->get();
        });
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $data['password'] = bcrypt('qwerty123'); // For testing
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
        $user = User::with('position')
            ->where('email', $email)
            ->first();
        return $user ?: null;
    }
}
