<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck\Format\Encoder;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Service\HealthCheck\Exception\OutputFormatNotFoundException;

class FormatEncoder implements FormatEncoderInterface
{
    /**
     * @var \Spryker\Service\HealthCheck\Format\FormatterInterface[]
     */
    protected $formatEncoders;

    /**
     * @param \Spryker\Service\HealthCheck\Format\FormatterInterface[] $formatEncoders
     */
    public function __construct(array $formatEncoders)
    {
        $this->formatEncoders = $formatEncoders;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     * @param string $formatName
     *
     * @throws \Spryker\Service\HealthCheck\Exception\OutputFormatNotFoundException
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function encode(HealthCheckResponseTransfer $healthCheckResponseTransfer, string $formatName): HealthCheckResponseTransfer
    {
        foreach ($this->formatEncoders as $formatEncoder) {
            if ($formatEncoder->getFormatName() === $formatName) {
                return $formatEncoder->formatMessage($healthCheckResponseTransfer);
            }
        }

        throw new OutputFormatNotFoundException();
    }
}
