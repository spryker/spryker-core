<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper\Persistence;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use Spryker\Shared\Kernel\AbstractBundleConfig;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;
use SprykerTest\Shared\Testify\Helper\ModuleNameTrait;

class FactoryHelper extends Module
{
    use ModuleNameTrait;

    /**
     * @var string
     */
    protected const PERSISTENCE_FACTORY_CLASS_NAME_PATTERN = '\%1$s\Zed\%2$s\Persistence\%2$sPersistenceFactory';

    /**
     * @var array<string, \Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory>
     */
    protected array $factoryStubs = [];

    /**
     * @param string $methodName
     * @param mixed $return
     * @param string|null $moduleName
     * @param string|null $applicationNamespace
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory
     */
    protected function mockPersistenceFactoryMethod(
        string $methodName,
        mixed $return,
        ?string $moduleName = null,
        ?string $applicationNamespace = null
    ): AbstractPersistenceFactory {
        $moduleName = $this->getModuleName($moduleName);
        $className = $this->getPersistenceFactoryClassName($moduleName, $applicationNamespace);

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        /** @var \Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory $factoryStubs */
        $factoryStubs = Stub::make($className, [
            $methodName => $return,
        ]);

        $this->factoryStubs[$moduleName] = $factoryStubs;

        return $factoryStubs;
    }

    /**
     * @param string|null $moduleName
     * @param string|null $applicationNamespace
     *
     * @return \Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory
     */
    public function getPersistenceFactory(?string $moduleName = null, ?string $applicationNamespace = null): AbstractPersistenceFactory
    {
        $moduleName = $this->getModuleName($moduleName);

        if (!empty($this->factoryStubs[$moduleName])) {
            return $this->injectConfig($this->factoryStubs[$moduleName], $moduleName);
        }

        $persistenceFactory = $this->createPersistenceFactory($moduleName, $applicationNamespace);

        return $this->injectConfig($persistenceFactory, $moduleName);
    }

    /**
     * @param string $moduleName
     * @param string|null $applicationNamespace
     *
     * @return \Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory
     */
    protected function createPersistenceFactory(string $moduleName, ?string $applicationNamespace = null): AbstractPersistenceFactory
    {
        $persistenceFactoryClassName = $this->getPersistenceFactoryClassName($moduleName, $applicationNamespace);

        return new $persistenceFactoryClassName();
    }

    /**
     * @param string $moduleName
     * @param string|null $applicationNamespace
     *
     * @return string
     */
    protected function getPersistenceFactoryClassName(string $moduleName, ?string $applicationNamespace = null): string
    {
        return sprintf(static::PERSISTENCE_FACTORY_CLASS_NAME_PATTERN, $applicationNamespace ?: 'Spryker', $moduleName);
    }

    /**
     * @param \Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory $persistenceFactory
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory
     */
    protected function injectConfig(AbstractPersistenceFactory $persistenceFactory, string $moduleName): AbstractPersistenceFactory
    {
        if (method_exists($persistenceFactory, 'setConfig') && $this->hasModule('\\' . ConfigHelper::class)) {
            $persistenceFactory->setConfig($this->getConfig($moduleName));
        }

        return $persistenceFactory;
    }

    /**
     * @param string $moduleName
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig|null
     */
    protected function getConfig(string $moduleName): ?AbstractBundleConfig
    {
        /** @var \Spryker\Zed\Kernel\AbstractBundleConfig|null $config */
        $config = $this->getConfigHelper()->getModuleConfig($moduleName);

        return $config;
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\ConfigHelper
     */
    protected function getConfigHelper(): ConfigHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\ConfigHelper $configHelper */
        $configHelper = $this->getModule('\\' . ConfigHelper::class);

        return $configHelper;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->factoryStubs = [];
    }
}
