<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\Finder;

use Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\PathBuilderInterface;
use Symfony\Component\Finder\Finder;

class FileFinder implements FinderInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\PathBuilderInterface
     */
    protected $pathBuilder;

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\PathBuilderInterface $pathBuilder
     */
    public function __construct(PathBuilderInterface $pathBuilder)
    {
        $this->pathBuilder = $pathBuilder;
    }

    /**
     * @param string $module
     *
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    public function find(string $module): array
    {
        $directories = $this->pathBuilder->buildPaths($module);
        $directories = array_filter($directories, function (string $directory) {
            return glob($directory);
        });

        if (count($directories) === 0) {
            return [];
        }

        $finder = new Finder();
        $finder->files()->in($directories);

        $finder->name('*.php');

        return iterator_to_array($finder);
    }
}
