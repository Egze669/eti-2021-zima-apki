<?php

namespace App\Repository;

use App\Model\UserCredentials;

interface UserRepositoryInterface
{
    /**
     * @param string $username
     * @return UserCredentials|null
     */
    public function findCredentialsByUsername(string $username): ?UserCredentials;
}