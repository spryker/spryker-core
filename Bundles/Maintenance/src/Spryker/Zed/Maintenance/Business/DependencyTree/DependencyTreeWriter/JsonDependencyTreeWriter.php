<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeWriter;

use Symfony\Component\Filesystem\Filesystem;

class JsonDependencyTreeWriter implements DependencyTreeWriterInterface
{

    /**
     * @var string
     */
    private $pathToFile;

    /**
     * @param string $pathToFile
     */
    public function __construct($pathToFile)
    {
        $this->pathToFile = $pathToFile;
    }

    /**
     * @param array $dependencyTree
     *
     * @return void
     */
    public function write(array $dependencyTree)
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->pathToFile, json_encode($dependencyTree, JSON_PRETTY_PRINT));
    }


}
