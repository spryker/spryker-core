<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Business\ModuleNamesFinder;

use Spryker\Zed\Kernel\KernelConfig;
use Symfony\Component\Finder\Finder;

class ModuleNamesFinder implements ModuleNamesFinderInterface
{
    /**
     * @var \Spryker\Zed\Kernel\KernelConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Kernel\KernelConfig $config
     */
    public function __construct(KernelConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return string[]
     */
    public function findModuleNames(): array
    {
        $moduleNames = [];

        $moduleNames = $this->addProjectModuleNames($moduleNames);
        $moduleNames = $this->addCoreModuleNames($moduleNames);

        ksort($moduleNames);

        return $moduleNames;
    }

    /**
     * @param string[] $moduleNames
     *
     * @return string[]
     */
    protected function addProjectModuleNames(array $moduleNames): array
    {
        $finder = new Finder();
        $finder->directories()->depth(0)->in($this->config->getPathsToProjectModules());

        foreach ($finder as $splFileInfo) {
            $moduleNames[$splFileInfo->getFilename()] = $splFileInfo->getFilename();
        }

        return $moduleNames;
    }

    /**
     * @param string[] $moduleNames
     *
     * @return string[]
     */
    protected function addCoreModuleNames(array $moduleNames): array
    {
        $finder = new Finder();
        $finder->directories()->depth(0)->in($this->config->getPathsToCoreModules());

        foreach ($finder as $splFileInfo) {
            $moduleNames[$splFileInfo->getFilename()] = $splFileInfo->getFilename();
        }

        return $moduleNames;
    }
}
