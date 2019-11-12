<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;

interface HealthCheckServiceInterface
{
    /**
     * Specification:
     * - Performs health checks for Yves services based on plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function checkYvesHealthCheck(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer;

    /**
     * Specification:
     * - Performs health checks for Zed services based on plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function checkZedHealthCheck(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer;

    /**
     * Specification:
     * - Performs health checks for Glue application services based on plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     */
    public function checkGlueHealthCheck(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer;
}
