<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface HealthCheckRequestFormatterInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\HealthCheckRequestTransfer
     */
    public function getHealthCheckRequestTransfer(RestRequestInterface $restRequest): HealthCheckRequestTransfer;
}
