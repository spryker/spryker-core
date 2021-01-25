<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheck\Processor\Mapper;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Generated\Shared\Transfer\RestHealthCheckResponseAttributesTransfer;

interface HealthCheckMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     * @param \Generated\Shared\Transfer\RestHealthCheckResponseAttributesTransfer $restHealthCheckResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestHealthCheckResponseAttributesTransfer
     */
    public function mapHealthCheckServiceResponseTransferToRestHealthCheckResponseAttributesTransfer(
        HealthCheckResponseTransfer $healthCheckResponseTransfer,
        RestHealthCheckResponseAttributesTransfer $restHealthCheckResponseAttributesTransfer
    ): RestHealthCheckResponseAttributesTransfer;
}
