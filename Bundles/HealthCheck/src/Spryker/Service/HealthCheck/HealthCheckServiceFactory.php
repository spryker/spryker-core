<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck;

use Spryker\Service\HealthCheck\Processor\ResponseProcessor;
use Spryker\Service\HealthCheck\Processor\ResponseProcessorInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\HealthCheck\Processor\ResponseProcessorInterface
     */
    public function createHealthCheckResponseProcessor(): ResponseProcessorInterface
    {
        return new ResponseProcessor(
            $this->getConfig()
        );
    }
}
