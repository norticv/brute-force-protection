<?php

namespace Nortic\BruteForceProtection;

class CoreFactory
{
    public function create(array $options = array())
    {
        return new Core($options['protectors'], $options['enabled']);
    }
}
