<?php

namespace Nortic\BruteForceProtection\IteratorStorage;

use Exception;

class File implements IteratorStorageInterface
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getIterator()
    {
        return (integer) @file_get_contents($this->path);
    }

    public function save($iterator)
    {
        if (@file_put_contents($this->path, $iterator) === false) {
            throw new Exception('Failed to write in file : '. $this->path .' check path or right to write');
        }
    }
}
