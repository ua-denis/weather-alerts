<?php

declare(strict_types=1);

namespace App\Domains\User\Repositories;

use App\Domains\User\Entities\User;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function getAll(): array;
}
