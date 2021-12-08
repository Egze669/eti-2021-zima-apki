<?php


namespace App\Repository;

use App\Model\UserCredentials;
use App\Security\Sha1PasswordEncoder;

class JsonUserRepository implements UserRepositoryInterface
{
    /**
     * @var array
     */
    private string $data;

    /**
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->data = "/var/www/eti-wprowadzenie/data/".$data."json";
    }

    /**
     * @param Sha1PasswordEncoder $encoder
     * @param array $users
     * @return JsonUserRepository
     */
    public static function createFromPlainPasswords(Sha1PasswordEncoder $encoder, array $users)
    {
        $encodedPasswords = [];
        foreach ($users as $username => $password) {
            $encodedPasswords[$username] = $encoder->encodePassword($password);
        }

        return new self($encodedPasswords);
    }
    public function findCredentialsByUsername(string $username): ?UserCredentials
    {
        $users = json_decode(file_get_contents("../data/users.json"), true);
        foreach ($users as $id => $table) {
            if($table['username']==$username){
                return new UserCredentials($username, $table['password']);
            }
        }
        return null;



    }
}