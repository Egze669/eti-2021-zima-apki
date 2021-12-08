<?php

namespace App;

use App\Controllers\DoLoginController;
use App\Controllers\LogoutController;
use App\Controllers\SimpleController;
use App\Repository\InMemoryUserRepository;
use App\Repository\JsonUserRepository;
use App\Security\Sha1PasswordEncoder;
use App\Session\Session;
use App\Controllers\PageController;

class ServiceContainer
{
    private static $instance;

    private $services;

    private function __construct()
    {
        $this->services['router'] = function () {
            $router = new Router();

            $router->addRoute('home', [
                'path' => '/',
                'controller' => function () use ($router) {
                    return new PageController($router, 'home', 'default');
                }
            ]);
            $router->addRoute('article', [
                'path' => '/article',
                'controller' => function () use ($router) {
                    return new PageController($router, 'article', 'default');
                }
            ]);
            $router->addRoute('body', [
                'path' => '/body',
                'controller' => function () use ($router) {
                    return new PageController($router, 'body', 'default');
                }
            ]);

            $router->addRoute('do_login', [
                'path' => '/do_login',
                'controller' => function() use ($router){
                    return new DoLoginController(
                        $this->get('session'),
                        $router,
                        $this->get('user_repository'),
                        $this->get('password_encoder')
                    );
                }
            ]);
            $router->addRoute('logout', [
                'path' => '/logout',
                'controller' => function() use ($router){
                    return new LogoutController(
                        $this->get('session'),
                        $router
                    );
                }
            ]);

            return $router;
        };
        $this->services['session'] = function (){
            return new Session();
        };
        $this->services['password_encoder'] = function (){
            return new Sha1PasswordEncoder();
        };
        $this->services['user_repository'] = function (){
            return InMemoryUserRepository::createFromPlainPasswords(
                $this->get('password_encoder'),
                [
                    'test1'=>'haslo',
                    'test2' =>'haslo2',
                    'tester' => 'haslo123'
                ]
        );
        };
    }

    /**
     * @return ServiceContainer
     */
    public static function getInstance(): ServiceContainer
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $id
     * @return mixed
     * @throws \Exception
     */
    public function get(string $id)
    {
        if (!$this->has($id)) {
            throw new \Exception(sprintf('Selected service %s was not found...', $id));
        }

        return $this->services[$id]($this);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id)
    {
        return isset($this->services[$id]);
    }
    /**
     * TODO Json
     * json decode
     * find usercredencials by name
     * repository jsonuserrepository
     * na $users sciezka bez create
     * find credentials poprawic
     * zamiast return InMemoryRepo do mojego repo
     */
}