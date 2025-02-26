<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $page, int $perPage): LengthAwarePaginator;

    /**
     * @return Collection
     */
    public function getAllPositions(): Collection;

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
}
