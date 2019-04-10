<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use ArrayObject;
use Codeception\Configuration;
use Codeception\Module;
use Codeception\Step;
use Codeception\TestInterface;
use ReflectionClass;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\AbstractLocatorLocator;
use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Testify\Locator\Business\BusinessLocator;

class LocatorHelper extends Module
{
    /**
     * @var array
     */
    protected $config = [
        'projectNamespaces' => [],
        'coreNamespaces' => [
            'SprykerShop',
            'Spryker',
        ],
    ];

    /**
     * @var array
     */
    protected $configCache;

    /**
     * @return void
     */
    public function _initialize()
    {
        Config::init();
        $reflectionProperty = $this->getConfigReflectionProperty();
        $this->configCache = $reflectionProperty->getValue()->getArrayCopy();
    }

    /**
     * @return \ReflectionProperty
     */
    protected function getConfigReflectionProperty()
    {
        $reflection = new ReflectionClass(Config::class);
        $reflectionProperty = $reflection->getProperty('config');
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty;
    }

    /**
     * @param string $key
     * @param array|bool|float|int|string $value
     *
     * @return void
     */
    public function setConfig($key, $value)
    {
        $configProperty = $this->getConfigReflectionProperty();
        $config = $configProperty->getValue();
        $config[$key] = $value;
        $configProperty->setValue($config);
    }

    /**
     * @param array $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = [])
    {
        $this->clearLocators();
        $this->clearCaches();
        $this->configureNamespacesForClassResolver();
    }

    /**
     * @return void
     */
    protected function clearLocators()
    {
        $reflection = new ReflectionClass(AbstractLocatorLocator::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        $instanceProperty->setValue(null);
    }

    /**
     * @return void
     */
    protected function clearCaches()
    {
        $reflection = new ReflectionClass(AbstractClassResolver::class);
        if ($reflection->hasProperty('cache')) {
            $instanceProperty = $reflection->getProperty('cache');
            $instanceProperty->setAccessible(true);
            $instanceProperty->setValue([]);
        }
    }

    /**
     * @param \Codeception\Step $step
     *
     * @return void
     */
    public function _beforeStep(Step $step)
    {
        $this->configureNamespacesForClassResolver();
    }

    /**
     * @return void
     */
    private function configureNamespacesForClassResolver()
    {
        $this->setConfig(KernelConstants::PROJECT_NAMESPACES, $this->config['projectNamespaces']);
        $this->setConfig(KernelConstants::CORE_NAMESPACES, $this->config['coreNamespaces']);
    }

    /**
     * @return \Spryker\Shared\Kernel\LocatorLocatorInterface|\Generated\Zed\Ide\AutoCompletion|\Generated\Service\Ide\AutoCompletion
     */
    public function getLocator()
    {
        return new BusinessLocator();
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function getFacade()
    {
        $currentNamespace = Configuration::config()['namespace'];
        $namespaceParts = explode('\\', $currentNamespace);
        $bundleName = lcfirst(end($namespaceParts));

        return $this->getLocator()->$bundleName()->facade();
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test)
    {
        $this->resetConfig();
    }

    /**
     * @return void
     */
    public function _afterSuite()
    {
        $this->resetConfig();
    }

    /**
     * @return void
     */
    private function resetConfig()
    {
        $reflectionProperty = $this->getConfigReflectionProperty();
        $reflectionProperty->setValue(new ArrayObject($this->configCache));
    }
}
