<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder;

use Symfony\Component\Finder\Finder;

class ZedNavigationSchemaFinder implements ZedNavigationSchemaFinderInterface
{
    /**
     * @var array
     */
    protected $pathPattern;

    /**
     * @var string
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
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function getSchemaFiles()
    {
        $finder = new Finder();
        $finder
            ->in($this->getPaths())
            ->name($this->fileNamePattern);

        return $finder;
    }

    /**
     * @return array
     */
    protected function getPaths()
    {
        $paths = [];
        foreach ($this->pathPattern as $pathPattern) {
            $paths = array_merge($paths, glob($pathPattern));
        }

        return $paths;
    }
}
