<?php

namespace Nortic\BruteForceProtection\IteratorStorage;

interface IteratorStorageInterface
{
    public function getIterator();
    public function save($iterator);
}

