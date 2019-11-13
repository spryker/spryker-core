<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck\Format\Encoder;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;

interface FormatEncoderInterface
{
    /**
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     * @param string $formatName
     *
     * @throws \Spryker\Service\HealthCheck\Exception\OutputFormatNotFoundException
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function encode(HealthCheckResponseTransfer $healthCheckResponseTransfer, string $formatName): HealthCheckResponseTransfer;
}
