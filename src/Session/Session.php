<?php

namespace App\Session;

class Session
{
    /**
     * @var bool
     */
    private bool $sessionStarted = false;

    private FlashMessages $flashMessages;

    public function __construct()
    {
        $this->flashMessages = new FlashMessages($this);
    }

    public function start()
    {
        $this->sessionStarted = true;
        session_start();
    }

    public function close()
    {
        if (!$this->sessionStarted) {
            $this->start();
        }
        session_write_close();
    }

    public function destroy()
    {
        if (!$this->sessionStarted) {
            $this->start();
        }
        session_destroy();
    }

    public function has(string $name): bool
    {
        if (!$this->sessionStarted) {
            $this->start();
        }
        return isset($_SESSION[$name]);
    }

    public function get(string $name, $default = null)
    {
        if (!$this->sessionStarted) {
            $this->start();
        }

        return $_SESSION[$name] ?? $default;
    }

    public function set(string $name, $value, $isGlobal = false)
    {
        if ($isGlobal) {
            $globals = json_decode(($_SESSION['globals'] ?? '[]'), true);
            $globals[$name] = null;
            $_SESSION['globals'] = json_encode($globals);
        }

        if (!$this->sessionStarted) {
            $this->start();
        }

        $_SESSION[$name] = $value;
    }

    public function remove(string $name)
    {
        unset($_SESSION[$name]);
    }

    public function regenerate()
    {
        if (!$this->sessionStarted) {
            $this->start();
        }
        $currentVars = [];
        $globals = json_decode($_SESSION['globals'] ?? '[]', true);

        foreach (array_keys($globals) as $key) {
            $currentVars[$key] = $this->get($key);
        }

        $this->destroy();
        session_id(session_create_id());
        $this->start();

        foreach ($currentVars as $key => $value) {
            $this->set($key, $value, true);
        }
        $_SESSION['globals'] = $globals;
    }
    public  function getFlashMessages()
    {
        return $this->flashMessages->getMessages();
    }
    public function setFlashMessage(string $type, string $message)
    {
        $this->flashMessages->addMessage($message,$type);
    }
    public function hasFlashMessages()
    {
        return !empty($this->flashMessages->peekMessages());
    }

}