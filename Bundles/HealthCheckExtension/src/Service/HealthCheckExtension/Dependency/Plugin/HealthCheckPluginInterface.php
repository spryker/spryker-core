<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheckExtension\Dependency\Plugin;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;

interface HealthCheckPluginInterface
{
    /**
     * Specification:
     * - Performs health check for the service.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function check(): HealthCheckResponseTransfer;
}
