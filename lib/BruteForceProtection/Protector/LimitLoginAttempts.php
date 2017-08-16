<?php

namespace Nortic\BruteForceProtection\Protector;

use Nortic\BruteForceProtection\IteratorStorage\IteratorStorageInterface;
use Exception;

class LimitLoginAttempts implements ProtectorInterface 
{
    const ACHIEVED_MESSAGE_CODE = 'achieved_limit_login_attemps';

    private $messagesTemplate = array(
        self::ACHIEVED_MESSAGE_CODE => 'Achieved limit login attemps'
    );

    private $retryMax;
    private $iterator;
    private $iteratorStorage;

    public function __construct($retryMax = 5, IteratorStorageInterface $iteratorStorage)
    {
        $this->retryMax         = $retryMax;
        $this->iteratorStorage  = $iteratorStorage;
        $this->iterator         = $iteratorStorage->getIterator();
    }

    public function check()
    {
        if ($this->iterator >= $this->retryMax) {

            $this->messages[self::ACHIEVED_MESSAGE_CODE] = $this->messagesTemplate[self::ACHIEVED_MESSAGE_CODE];

            return false;
        }

        return true;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function successLogin()
    {
        $this->iterator = 0;
        $this->iteratorStorage->save($this->iterator);
    }

    public function failedLogin()
    {
        $this->iterator++;
        $this->iteratorStorage->save($this->iterator);
    }
}
