<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Storage;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Storage\StorageServiceFactory getFactory()
 */
class StorageService extends AbstractService implements StorageServiceInterface
{
    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function checkStorageHealthIndicator(): HealthCheckServiceResponseTransfer
    {
        return $this->getFactory()->createStorageHealthIndicator()->executeHealthCheck();
    }
}
