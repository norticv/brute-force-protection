<?php

namespace Nortic\BruteForceProtection;

use Nortic\BruteForceProtection\Protector\ProtectorInterface;

interface CoreInterface
{
    public function addProtector(ProtectorInterface $protector);
    public function check();
    public function successLogin();
    public function failedLogin();
}
