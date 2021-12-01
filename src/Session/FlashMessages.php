<?php


namespace App\Session;


class FlashMessages
{
    /**
     * @var array
     */
    private $messages;
    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->messages = unserialize($session->get('flash_messages'));
    }
    public function peekMessages()
    {
        return $this->messages;
    }
    private function store()
    {
        $this->session->set('flash_messages',serialize($this->messages));
    }

    /**
     * @return array
     */
    public function getMessages():array
    {
        $messages = $this->messages;
        $this->messages = [];
        $this->store();

        return $messages;
    }

    public function addMessage(string $messages, string $type)
    {
        $this->messages[$type][] = $messages;
        $this->store();
    }
}