<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Business\ClassResolver\ResolvableCache\CacheBuilder;

use Spryker\Shared\Kernel\ClassResolver\ClassNameFinder\ClassNameFinderInterface;
use Spryker\Zed\Kernel\Business\ClassResolver\ResolvableCache\CacheWriter\CacheWriterInterface;
use Spryker\Zed\Kernel\Business\ModuleNamesFinder\ModuleNamesFinderInterface;
use Spryker\Zed\Kernel\KernelConfig;

class CacheBuilder implements CacheBuilderInterface
{
    /**
     * @var \Spryker\Zed\Kernel\Business\ModuleNamesFinder\ModuleNamesFinderInterface
     */
    protected $moduleNameFinder;

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ClassNameFinder\ClassNameFinderInterface
     */
    protected $classNameFinder;

    /**
     * @var \Spryker\Zed\Kernel\Business\ClassResolver\ResolvableCache\CacheWriter\CacheWriterInterface
     */
    protected $cacheWriter;

    /**
     * @var \Spryker\Zed\Kernel\KernelConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Kernel\Business\ModuleNamesFinder\ModuleNamesFinderInterface $moduleNameFinder
     * @param \Spryker\Shared\Kernel\ClassResolver\ClassNameFinder\ClassNameFinderInterface $classNameFinder
     * @param \Spryker\Zed\Kernel\Business\ClassResolver\ResolvableCache\CacheWriter\CacheWriterInterface $cacheWriter
     * @param \Spryker\Zed\Kernel\KernelConfig $config
     */
    public function __construct(
        ModuleNamesFinderInterface $moduleNameFinder,
        ClassNameFinderInterface $classNameFinder,
        CacheWriterInterface $cacheWriter,
        KernelConfig $config
    ) {
        $this->moduleNameFinder = $moduleNameFinder;
        $this->classNameFinder = $classNameFinder;
        $this->cacheWriter = $cacheWriter;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function build(): void
    {
        $cacheEntries = [];

        $moduleNames = $this->getModuleNames();

        foreach ($moduleNames as $moduleName) {
            $cacheEntries = $this->addCacheEntriesForModule($moduleName, $cacheEntries);
        }

        $this->cacheWriter->write($cacheEntries);
    }

    /**
     * @param string $moduleName
     * @param string[] $cacheEntries
     *
     * @return string[]
     */
    protected function addCacheEntriesForModule(string $moduleName, array $cacheEntries): array
    {
        foreach ($this->config->getResolvableTypeClassNamePatternMap() as $resolvableType => $classNamePattern) {
            $className = $this->classNameFinder->findClassName($moduleName, $classNamePattern, false);
            if ($className !== null) {
                $cacheEntries[$moduleName . $resolvableType] = $className;
            }
        }

        return $cacheEntries;
    }

    /**
     * @return string[]
     */
    protected function getModuleNames(): array
    {
        return $this->moduleNameFinder->findModuleNames();
    }
}
