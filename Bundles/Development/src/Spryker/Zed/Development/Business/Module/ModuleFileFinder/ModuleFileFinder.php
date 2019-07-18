<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\ModuleFileFinder;

use Generated\Shared\Transfer\ModuleTransfer;
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
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return bool
     */
    public function hasFiles(ModuleTransfer $moduleTransfer): bool
    {
        $directories = $this->getModuleDirectories($moduleTransfer);

        if (count($directories) === 0) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function find(ModuleTransfer $moduleTransfer): Finder
    {
        $directories = $this->getModuleDirectories($moduleTransfer);

        $finder = new Finder();
        $finder->files()->in($directories)->ignoreDotFiles(false);

        return $finder;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    protected function getModuleDirectories(ModuleTransfer $moduleTransfer): array
    {
        $directories = $this->pathBuilder->buildPaths($moduleTransfer);
        $directories = array_filter($directories, 'glob');

        return $directories;
    }
}
