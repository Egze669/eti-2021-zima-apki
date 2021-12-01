<?php

namespace App\Repository;

use App\Model\UserCredentials;
use App\Security\Sha1PasswordEncoder;

class InMemoryUserRepository implements UserRepositoryInterface
{
    /**
     * @var array
     */
    private $users;

    /**
     * @param array $users
     */
    public function __construct(array $users = [])
    {
        $this->users = $users;
    }

    /**
     * @param array $users
     * @return InMemoryUserRepository
     */
    public static function createFromPlainPasswords(Sha1PasswordEncoder $encoder, array $users)
    {
        $encodedPasswords = [];
        foreach ($users as $username => $password) {
            $encodedPasswords[$username] = $encoder->encodePassword($password);
        }

        return new self($encodedPasswords);
    }

    /**
     * @param string $username
     * @return UserCredentials|null
     */
    public function findCredentialsByUsername(string $username): ?UserCredentials
    {
        if (!isset($this->users[$username])) {
            return null;
        }

        return new UserCredentials($username, $this->users[$username]);
    }
}