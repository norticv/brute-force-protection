<?php

namespace Nortic\BruteForceProtection\Protector;

interface ProtectorInterface
{
    public function check();
    public function getMessages();
    public function successLogin();
    public function failedLogin();
}
