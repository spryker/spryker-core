<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Transfer\Business\Model;

use Symfony\Component\Filesystem\Filesystem;

class TransferCleaner
{

    /**
     * @var string
     */
    private $directory;

    /**
     * @param string $directory
     */
    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return void
     */
    public function cleanDirectory()
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove($this->directory);
    }

}
