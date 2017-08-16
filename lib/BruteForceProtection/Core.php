<?php

namespace Nortic\BruteForceProtection;

use Nortic\BruteForceProtection\Protector\ProtectorInterface;

class Core implements CoreInterface
{
    private $protectors = array();
    private $enabled = true;

    public function __construct(array $protectors = array(), $enabled = true)
    {
        foreach ($protectors as $protector) {
            $this->addProtector($protector);
        }

        $this->enabled = $enabled;
    }

    public function addProtector(ProtectorInterface $protector)
    {
        $this->protectors[] = $protector;
    }

    public function check()
    {
        if ( ! $this->enabled) {
            return true;
        }

        $success = true;

        foreach ($this->getProtectors() as $protector) {
            if ( ! $protector->check()) {
                $success = false;
            }
        }

        return $success;
    }

    public function successLogin()
    {
        if ( ! $this->enabled) {
            return ;
        }

        foreach ($this->getProtectors() as $protector) {
            $protector->successLogin();
        }
    }

    public function failedLogin()
    {
        if ( ! $this->enabled) {
            return ;
        }

        foreach ($this->getProtectors() as $protector) {
            $protector->failedLogin();
        }
    }

    public function getMessages()
    {
        $messages = array();

        foreach ($this->getProtectors() as $protector) {
            $messages = array_merge($messages, $protector->getMessages());
        }

        return $messages;
    }

    private function getProtectors()
    {
        if ( ! count($this->protectors)) {
            throw new Exception\NoneProtectors('None protectors loaded');
        }

        return $this->protectors;
    }
}
