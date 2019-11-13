<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck\Format;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;

class ConsoleFormatter implements FormatterInterface
{
    protected const FORMAT_NAME = 'console';

    protected const OUTPUT_SUCCESS_COLOR = 'green';
    protected const OUTPUT_ERROR_COLOR = 'red';

    protected const OUTPUT_SUCCESS_MESSAGE = 'Healthy';
    protected const OUTPUT_ERROR_MESSAGE = 'Unhealthy';

    /**
     * @return string
     */
    public function getFormatName(): string
    {
        return static::FORMAT_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function formatMessage(HealthCheckResponseTransfer $healthCheckResponseTransfer): HealthCheckResponseTransfer
    {
        $message = '';
        foreach ($healthCheckResponseTransfer->getHealthCheckServiceResponses() as $healthCheckServiceResponseTransfer) {
            $serviceName = $healthCheckServiceResponseTransfer->getName();
            $serviceStatus = $healthCheckServiceResponseTransfer->getStatus();
            $outputColor = $serviceStatus ? static::OUTPUT_SUCCESS_COLOR : static::OUTPUT_ERROR_COLOR;
            $outputStatus = $serviceStatus ? static::OUTPUT_SUCCESS_MESSAGE : static::OUTPUT_ERROR_MESSAGE;
            $message .= sprintf("Service $serviceName: <fg=$outputColor;options=bold>%s</>\n", $outputStatus);

            if ($serviceStatus === false) {
                $message .= sprintf("<fg=$outputColor;options=bold>Error Message: %s</>\n", $healthCheckServiceResponseTransfer->getMessage());
            }
        }

        return $healthCheckResponseTransfer
            ->setMessage($message);
    }
}
