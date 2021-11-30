<?php


namespace App;


class FlashMessages
{
    /**
     * @var array
     */
    private $messages;

    /**
     * @return bool
     */
    public function hasMessages()
    {
        if (is_null($this->messages)) {
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        $messages = $this->messages;
        $this->messages = [];
        return $messages;
    }

    public function addMessage(string $message, string $type)
    {
        $this->messages[] = $type -> $message;
    }
}