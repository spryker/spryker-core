<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Configuration;
use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use Spryker\Shared\Kernel\AbstractSharedConfig;
use Spryker\Shared\Kernel\AbstractSharedFactory;

class FactoryHelper extends Module
{
    protected const SHARED_FACTORY_CLASS_NAME_PATTERN = '\%1$s\Shared\%2$s\%2$sSharedFactory';

    /**
     * @var array
     */
    protected $mockedSharedFactoryMethods = [];

    /**
     * @var \Spryker\Shared\Kernel\AbstractSharedFactory|null
     */
    protected $sharedFactoryStub;

    /**
     * @param string $methodName
     * @param mixed $return
     *
     * @throws \Exception
     *
     * @return object|\Spryker\Shared\Kernel\AbstractSharedFactory
     */
    public function mockSharedFactoryMethod(string $methodName, $return)
    {
        $className = $this->getSharedFactoryClassName();

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        $this->mockedSharedFactoryMethods[$methodName] = $return;

        /** @var \Spryker\Shared\Kernel\AbstractSharedFactory $sharedFactoryStub */
        $sharedFactoryStub = Stub::make($className, $this->mockedSharedFactoryMethods);

        $this->sharedFactoryStub = $sharedFactoryStub;

        return $this->sharedFactoryStub;
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractSharedFactory
     */
    public function getSharedFactory(): AbstractSharedFactory
    {
        if ($this->sharedFactoryStub !== null) {
            return $this->injectSharedConfig($this->sharedFactoryStub);
        }

        $sharedFactory = $this->createSharedFactory();
        $sharedFactory = $this->injectSharedConfig($sharedFactory);

        return $sharedFactory;
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractSharedFactory
     */
    protected function createSharedFactory(): AbstractSharedFactory
    {
        $sharedFactoryClassName = $this->getSharedFactoryClassName();

        return new $sharedFactoryClassName();
    }

    /**
     * @return string
     */
    protected function getSharedFactoryClassName(): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf(static::SHARED_FACTORY_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[2]);
    }

    /**
     * @param \Spryker\Shared\Kernel\AbstractSharedFactory $sharedFactory
     *
     * @return \Spryker\Shared\Kernel\AbstractSharedFactory
     */
    protected function injectSharedConfig(AbstractSharedFactory $sharedFactory): AbstractSharedFactory
    {
        if (method_exists($sharedFactory, 'setSharedConfig') && $this->hasModule('\\' . ConfigHelper::class)) {
            $sharedConfig = $this->getSharedConfig();
            if ($sharedFactory !== null) {
                $sharedFactory->setSharedConfig($sharedConfig);
            }
        }

        return $sharedFactory;
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractSharedConfig|null
     */
    protected function getSharedConfig(): ?AbstractSharedConfig
    {
        /** @var \Spryker\Shared\Kernel\AbstractSharedConfig|null $sharedConfig */
        $sharedConfig = $this->getConfigHelper()->getSharedModuleConfig();

        return $sharedConfig;
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\ConfigHelper
     */
    protected function getConfigHelper(): ConfigHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\ConfigHelper $sharedConfigHelper */
        $sharedConfigHelper = $this->getModule('\\' . ConfigHelper::class);

        return $sharedConfigHelper;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->sharedFactoryStub = null;
        $this->mockedSharedFactoryMethods = [];
    }
}
