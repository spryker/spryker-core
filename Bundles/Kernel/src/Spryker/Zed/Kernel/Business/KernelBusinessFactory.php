<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Zed\Kernel\Business\ClassResolver\ResolvableCache\CacheBuilder\CacheBuilder;
use Spryker\Zed\Kernel\Business\ClassResolver\ResolvableCache\CacheBuilder\CacheBuilderInterface;
use Spryker\Zed\Kernel\Business\ClassResolver\ResolvableCache\CacheWriter\CacheWriterInterface;
use Spryker\Zed\Kernel\Business\ClassResolver\ResolvableCache\CacheWriter\CacheWriterPhp;
use Spryker\Zed\Kernel\Business\ModuleNamesFinder\ModuleNamesFinder;
use Spryker\Zed\Kernel\Business\ModuleNamesFinder\ModuleNamesFinderInterface;

/**
 * @method \Spryker\Zed\Kernel\KernelConfig getConfig()
 * @method \Spryker\Shared\Kernel\KernelSharedFactory getSharedFactory()
 */
class KernelBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Kernel\Business\ClassResolver\ResolvableCache\CacheBuilder\CacheBuilderInterface
     */
    public function createCacheBuilder(): CacheBuilderInterface
    {
        return new CacheBuilder(
            $this->createModuleNamesFinder(),
            $this->getSharedFactory()->createClassNameFinder(),
            $this->createCacheWriter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\ModuleNamesFinder\ModuleNamesFinderInterface
     */
    public function createModuleNamesFinder(): ModuleNamesFinderInterface
    {
        return new ModuleNamesFinder($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\ClassResolver\ResolvableCache\CacheWriter\CacheWriterInterface
     */
    public function createCacheWriter(): CacheWriterInterface
    {
        return new CacheWriterPhp($this->getConfig());
    }
}
