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
     * @param array $pathPattern
     */
    public function __construct(array $pathPattern)
    {
        $this->pathPattern = $pathPattern;
    }

    /**
     * @param string $fileNamePattern
     *
     * @return \Symfony\Component\Finder\Finder<\Symfony\Component\Finder\SplFileInfo>
     */
    public function getSchemaFiles(string $fileNamePattern)
    {
        $finder = new Finder();
        $finder
            ->in($this->getPaths())
            ->name($fileNamePattern);

        return $finder;
    }

    /**
     * @return array
     */
    protected function getPaths()
    {
        $paths = [];
        foreach ($this->pathPattern as $pathPattern) {
            $paths = array_merge(
                $paths,
                glob($pathPattern, GLOB_NOSORT | GLOB_ONLYDIR)
            );
        }

        return $paths;
    }
}
