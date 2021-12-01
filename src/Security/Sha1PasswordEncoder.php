<?php

namespace App\Security;

class Sha1PasswordEncoder
{
    public function encodePassword(string $plainPassword): string
    {
        return sha1($plainPassword);
    }
}

