<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Codeception\TestInterface;
use ReflectionClass;
use SprykerTest\Shared\Testify\Helper\Config\ConfigFileStrategy;
use SprykerTest\Shared\Testify\Helper\Config\ConfigReflectionStrategy;

class ConfigHelper extends Module
{

    /**
     * @var \SprykerTest\Shared\Testify\Helper\Config\ConfigStrategyInterface
     */
    protected $configStrategy;

    /**
     * @return \SprykerTest\Shared\Testify\Helper\Config\ConfigStrategyInterface
     */
    protected function getConfigStrategy()
    {
        if (!$this->configStrategy) {
            $this->configStrategy = $this->createConfigStrategy();
        }

        return $this->configStrategy;
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\Config\ConfigFileStrategy|\SprykerTest\Shared\Testify\Helper\Config\ConfigReflectionStrategy|\SprykerTest\Shared\Testify\Helper\Config\ConfigStrategyInterface
     */
    protected function createConfigStrategy()
    {
        $moduleContainerConfig = $this->getModuleContainerConfig();
        $className = $moduleContainerConfig['class_name'];
        if (!preg_match('/PresentationTester/', $className)) {
            $this->configStrategy = new ConfigReflectionStrategy();
            $this->configStrategy->storeConfig();

            return $this->configStrategy;
        }

        $this->configStrategy = new ConfigFileStrategy();
        $this->configStrategy->storeConfig();

        return $this->configStrategy;
    }

    /**
     * @return array
     */
    protected function getModuleContainerConfig()
    {
        $moduleContainer = new ReflectionClass(ModuleContainer::class);
        $configProperty = $moduleContainer->getProperty('config');
        $configProperty->setAccessible(true);

        return $configProperty->getValue($this->moduleContainer);
    }

    /**
     * @param string $key
     * @param array|bool|float|int|string $value
     *
     * @return void
     */
    public function setConfig($key, $value)
    {
        $this->getConfigStrategy()->setConfig($key, $value);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function removeConfig($key)
    {
        $this->getConfigStrategy()->removeConfig($key);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test)
    {
        $this->getConfigStrategy()->resetConfig();
    }

    /**
     * @return void
     */
    public function _afterSuite()
    {
        $this->getConfigStrategy()->resetConfig();
    }

}
