<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\ModuleFileFinder;

use Exception;
use Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface;
use Symfony\Component\Finder\Finder;

class ModuleFileFinder implements ModuleFileFinderInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface
     */
    protected $pathBuilder;

    /**
     * @param \Spryker\Zed\Development\Business\Module\PathBuilder\PathBuilderInterface $pathBuilder
     */
    public function __construct(PathBuilderInterface $pathBuilder)
    {
        $this->pathBuilder = $pathBuilder;
    }

    /**
     * @param string $module
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function find(string $module): Finder
    {
        $directories = $this->pathBuilder->buildPaths($module);
        $directories = array_filter($directories, 'glob');

        if (count($directories) === 0) {
            throw new Exception(sprintf('Could not find directories for the "%s" module.', $module));
        }

        $finder = new Finder();
        $finder->files()->in($directories)->ignoreDotFiles(false);

        return $finder;
    }
}
