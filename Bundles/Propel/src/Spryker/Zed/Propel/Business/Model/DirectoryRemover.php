<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Business\Model;

use Symfony\Component\Filesystem\Filesystem;

class DirectoryRemover implements DirectoryRemoverInterface
{

    /**
     * @var string
     */
    private $directoryToRemove;

    /**
     * @param string $directoryToRemove
     */
    public function __construct($directoryToRemove)
    {
        $this->directoryToRemove = $directoryToRemove;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->directoryToRemove);
    }

}
