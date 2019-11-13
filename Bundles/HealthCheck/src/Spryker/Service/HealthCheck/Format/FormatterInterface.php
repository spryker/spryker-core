<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck\Format;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;

interface FormatterInterface
{
    /**
     * @return string
     */
    public function getFormatName(): string;

    /**
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function formatMessage(HealthCheckResponseTransfer $healthCheckResponseTransfer): HealthCheckResponseTransfer;
}
