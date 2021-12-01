<?php

namespace App\Controllers;

use App\Repository\UserRepositoryInterface;
use App\Request;
use App\Response\LayoutResponse;
use App\Response\RedirectResponse;
use App\Response\Response;
use App\Router;
use App\Security\Sha1PasswordEncoder;
use App\ServiceContainer;
use App\Session\Session;

class DoLoginController implements ControllerInterface
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var Router
     */
    private Router $router;
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;
    /**
     * @var Sha1PasswordEncoder
     */
    private Sha1PasswordEncoder $passwordEncoder;

    /**
     * @param Session $session
     * @param Router $router
     */
    public function __construct(
        Session $session,
        Router $router,
        UserRepositoryInterface $repository,
        Sha1PasswordEncoder $passwordEncoder
    ) {
        $this->session = $session;
        $this->router = $router;
        $this->repository = $repository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $isValidUser = false;
        $response = new RedirectResponse(
            $this->router->generate('home')
        );

        $isPost = $request->isPost();
        if (!$isPost) {
            return $response;
        }

        $userName = $request->getPost('login');
        $password = $request->getPost('password');
        $credentials = $this->repository->findCredentialsByUsername($userName);

        if ($credentials) {
            $encodedPass = $this->passwordEncoder->encodePassword($password);
            $isValidUser = $encodedPass === $credentials->getPassword();
        }

        if ($isValidUser) {
            $this->session->regenerate();
            $this->session->set('user', $request->getPost('login'));
            $this->session->setFlashMessage('success', 'Pomyślnie zalogowano do systemu');
        } else {
            $this->session->setFlashMessage('error', 'Podane dane są nieprawidłowe.');
        }

        return $response;
    }
}