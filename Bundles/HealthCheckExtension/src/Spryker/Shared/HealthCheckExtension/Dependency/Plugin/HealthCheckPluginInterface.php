<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheckExtension\Dependency\Plugin;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;

interface HealthCheckPluginInterface
{
    /**
     * Specification:
     * - Defined the name of service.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Specification:
     * - Performs health check for the service.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function check(): HealthCheckServiceResponseTransfer;
}
