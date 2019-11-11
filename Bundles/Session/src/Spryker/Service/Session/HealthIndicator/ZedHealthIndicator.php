<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Session\HealthIndicator;

use Exception;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;

class ZedHealthIndicator implements HealthIndicatorInterface
{
    protected const KEY_HEALTH_CHECK = 'healthCheck';

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeHealthCheck(): HealthCheckServiceResponseTransfer
    {
        try {
            $_SESSION[self::KEY_HEALTH_CHECK] = 'ok';
            $_SESSION[self::KEY_HEALTH_CHECK];
        } catch (Exception $e) {
            return (new HealthCheckServiceResponseTransfer())
                ->setStatus(false)
                ->setMessage($e->getMessage());
        }

        return (new HealthCheckServiceResponseTransfer())
            ->setStatus(true);
    }
}
