<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use ReflectionClass;
use Spryker\Client\Kernel\AbstractFactory as ClientAbstractFactory;
use Spryker\Glue\Kernel\AbstractFactory as GlueAbstractFactory;
use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Shared\Kernel\ContainerMocker\ContainerGlobals;
use Spryker\Yves\Kernel\AbstractFactory as YvesAbstractFactory;
use Spryker\Zed\Kernel\AbstractFactory as ZedAbstractFactory;

class DependencyHelper extends Module
{
    use FactoryHelperTrait;

    /**
     * @var \Spryker\Shared\Kernel\ContainerMocker\ContainerGlobals
     */
    private $containerGlobals;

    /**
     * @return void
     */
    public function _initialize(): void
    {
        $this->containerGlobals = new ContainerGlobals();
    }

    /**
     * @return void
     */
    public function clearFactoryContainerCache(): void
    {
        $factoriesArray = [
            ClientAbstractFactory::class,
            ZedAbstractFactory::class,
            YvesAbstractFactory::class,
            GlueAbstractFactory::class,
            AbstractServiceFactory::class,
        ];

        foreach ($factoriesArray as $factory) {
            $factory = new ReflectionClass($factory);
            $containerProperty = $factory->getProperty('containers');
            $containerProperty->setAccessible(true);
            $containerProperty->setValue([]);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string|null $onlyFor
     *
     * @return void
     */
    public function setDependency(string $key, $value, ?string $onlyFor = null): void
    {
        $this->containerGlobals->set($key, $value, $onlyFor);
        $this->clearFactoryContainerCache();
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->containerGlobals->reset();
        $this->clearFactoryContainerCache();
    }
}
