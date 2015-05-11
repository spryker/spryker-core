<?php

namespace SprykerEngine\Zed\Transfer\Business\Model;

use Symfony\Component\Filesystem\Filesystem;

class TransferCleaner
{

    /**
     * @var string
     */
    private $directory;

    /**
     * @param $directory
     */
    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function cleanDirectory()
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove($this->directory);
    }
}
