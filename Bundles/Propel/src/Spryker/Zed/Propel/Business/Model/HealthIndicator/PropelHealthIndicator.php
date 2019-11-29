<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\HealthIndicator;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Propel\Runtime\Exception\RuntimeException;
use Propel\Runtime\Propel;

class PropelHealthIndicator implements HealthIndicatorInterface
{
    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeHealthCheck(): HealthCheckServiceResponseTransfer
    {
        try {
            Propel::getConnection()->getName();
        } catch (RuntimeException $e) {
            return (new HealthCheckServiceResponseTransfer())
                ->setStatus(false)
                ->setMessage($e->getMessage());
        }

        return (new HealthCheckServiceResponseTransfer())
            ->setStatus(true);
    }
}
