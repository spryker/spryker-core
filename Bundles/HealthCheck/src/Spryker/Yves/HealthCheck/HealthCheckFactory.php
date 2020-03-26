<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\HealthCheck;

use Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface;
use Spryker\Shared\HealthCheck\ChainFilter\Filter\ServiceNameFilter;
use Spryker\Shared\HealthCheck\ChainFilter\FilterInterface;
use Spryker\Shared\HealthCheck\ChainFilter\ServiceChainFilter;
use Spryker\Shared\HealthCheck\Processor\HealthCheckProcessor;
use Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface;
use Spryker\Shared\HealthCheck\Processor\ResponseProcessor;
use Spryker\Shared\HealthCheck\Processor\ResponseProcessorInterface;
use Spryker\Shared\HealthCheck\Validator\ServiceNameValidator;
use Spryker\Shared\HealthCheck\Validator\ValidatorInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Yves\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface
     */
    public function createHealthCheckProcessor(): HealthCheckProcessorInterface
    {
        return new HealthCheckProcessor(
            $this->createServiceNameValidator(),
            $this->createServiceChainFilter(),
            $this->createResponseProcessor(),
            $this->getHealthCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\HealthCheck\Validator\ValidatorInterface
     */
    public function createServiceNameValidator(): ValidatorInterface
    {
        return new ServiceNameValidator();
    }

    /**
     * @return \Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface
     */
    public function createServiceChainFilter(): ChainFilterInterface
    {
        $chainFilter = new ServiceChainFilter();
        $chainFilter
            ->addFilter($this->createServiceNameFilter());

        return $chainFilter;
    }

    /**
     * @return \Spryker\Shared\HealthCheck\ChainFilter\FilterInterface
     */
    public function createServiceNameFilter(): FilterInterface
    {
        return new ServiceNameFilter();
    }

    /**
     * @return \Spryker\Shared\HealthCheck\Processor\ResponseProcessorInterface
     */
    public function createResponseProcessor(): ResponseProcessorInterface
    {
        return new ResponseProcessor(
            $this->getConfig()->isHealthCheckEnabled()
        );
    }

    /**
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function getHealthCheckPlugins(): array
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK);
    }
}
