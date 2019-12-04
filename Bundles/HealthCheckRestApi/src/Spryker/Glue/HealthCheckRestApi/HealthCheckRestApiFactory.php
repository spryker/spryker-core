<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheckRestApi;

use Spryker\Glue\HealthCheckRestApi\Dependency\Client\HealthCheckRestApiToHealthCheckClientInterface;
use Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck\HealthCheckProcessor;
use Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck\HealthCheckProcessorInterface;
use Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck\HealthCheckRequestFormatter;
use Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck\HealthCheckRequestFormatterInterface;
use Spryker\Glue\HealthCheckRestApi\Processor\Mapper\HealthCheckMapper;
use Spryker\Glue\HealthCheckRestApi\Processor\Mapper\HealthCheckMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig getConfig()
 */
class HealthCheckRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck\HealthCheckProcessorInterface
     */
    public function createHealthCheckProcessor(): HealthCheckProcessorInterface
    {
        return new HealthCheckProcessor(
            $this->getHealthCheckClient(),
            $this->getResourceBuilder(),
            $this->createHealthCheckMapper(),
            $this->createHealthCheckRequestFormatter()
        );
    }

    /**
     * @return \Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck\HealthCheckRequestFormatterInterface
     */
    public function createHealthCheckRequestFormatter(): HealthCheckRequestFormatterInterface
    {
        return new HealthCheckRequestFormatter(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\HealthCheckRestApi\Processor\Mapper\HealthCheckMapperInterface
     */
    public function createHealthCheckMapper(): HealthCheckMapperInterface
    {
        return new HealthCheckMapper();
    }

    /**
     * @return \Spryker\Glue\HealthCheckRestApi\Dependency\Client\HealthCheckRestApiToHealthCheckClientInterface
     */
    public function getHealthCheckClient(): HealthCheckRestApiToHealthCheckClientInterface
    {
        return $this->getProvidedDependency(HealthCheckRestApiDependencyProvider::CLIENT_HEALTH_CHECK);
    }
}
