<?php

namespace SprykerFeature\Zed\Setup\Business\Model;

use Symfony\Component\Filesystem\Filesystem;

class GeneratedDirectoryRemover
{

    /**
     * @var string
     */
    private $directoryToRemove;

    /**
     * @param $directoryToRemove
     */
    public function __construct($directoryToRemove)
    {
        $this->directoryToRemove = $directoryToRemove;
    }

    public function execute()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->directoryToRemove);
    }
}
