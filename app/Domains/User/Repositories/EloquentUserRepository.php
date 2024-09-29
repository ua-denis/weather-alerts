<?php

declare(strict_types=1);

namespace App\Domains\User\Repositories;

use App\Domains\User\Entities\User;
use App\Models\User as EloquentUser;
use Illuminate\Database\Eloquent\Collection;

class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->mapToDomainUsers(EloquentUser::all());
    }

    /**
     * @param  Collection  $eloquentUsers
     * @return User[]
     */
    private function mapToDomainUsers(Collection $eloquentUsers): array
    {
        return $eloquentUsers->map(function (EloquentUser $user) {
            return $this->createUserEntity($user);
        })->filter()->toArray();
    }

    /**
     * @param  EloquentUser  $user
     * @return User|null
     */
    private function createUserEntity(EloquentUser $user): ?User
    {
        $email = $user->email ?? '';

        return !empty($email) ? new User(
            id: $user->id,
            email: $email,
            city: $user->city ?? ''
        ) : null;
    }
}
