<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\SchemaFinder;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class NavigationSchemaFinder implements NavigationSchemaFinderInterface
{

    /**
     * @var array
     */
    protected $pathPattern;

    /**
     * @var array
     */
    protected $fileNamePattern;

    /**
     * @param array $pathPattern
     * @param string $fileNamePattern
     */
    public function __construct(array $pathPattern, $fileNamePattern)
    {
        $this->pathPattern = $pathPattern;
        $this->fileNamePattern = $fileNamePattern;
    }

    /**
     * @return Finder|SplFileInfo[]
     */
    public function getSchemaFiles()
    {
        $finder = new Finder();
        $finder
            ->in($this->pathPattern)
            ->name($this->fileNamePattern)
        ;

        return $finder;
    }

}
