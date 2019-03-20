<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Configuration;
use Codeception\Module;
use Codeception\Step;
use ReflectionClass;
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
        $configModule = $this->getConfigModule();
        $configModule->setConfig(KernelConstants::PROJECT_NAMESPACES, $this->config['projectNamespaces']);
        $configModule->setConfig(KernelConstants::CORE_NAMESPACES, $this->config['coreNamespaces']);
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\ConfigHelper
     */
    protected function getConfigModule(): ConfigHelper
    {
        return $this->getModule('\\' . ConfigHelper::class);
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
}
