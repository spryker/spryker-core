<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel;

use Spryker\Glue\Kernel\ClassResolver\Config\BundleConfigResolver;
use Spryker\Glue\Kernel\ClassResolver\Factory\FactoryResolver;

abstract class AbstractPlugin implements ModuleNameAwareInterface
{
    /**
     * @var \Spryker\Glue\Kernel\AbstractFactory
     */
    private $factory;

    /**
     * @var \Spryker\Glue\Kernel\AbstractBundleConfig
     */
    private $config;

    /**
     * @return \Spryker\Glue\Kernel\AbstractFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        $calledClass = $this->getFactoryResolver()->setCallerClass($this);
        $classInfo = $calledClass->getClassInfo();

        return $classInfo->getBundle();
    }

    /**
     * @return \Spryker\Glue\Kernel\AbstractFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Glue\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @return \Spryker\Glue\Kernel\AbstractBundleConfig
     */
    protected function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->resolveBundleConfig();
        }

        return $this->config;
    }

    /**
     * @return \Spryker\Glue\Kernel\AbstractBundleConfig
     */
    private function resolveBundleConfig()
    {
        return $this->getBundleConfigResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Glue\Kernel\ClassResolver\Config\BundleConfigResolver
     */
    private function getBundleConfigResolver()
    {
        return new BundleConfigResolver();
    }
}
