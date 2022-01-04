<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Business;

use Spryker\Zed\AvailabilityStorage\Business\Storage\AvailabilityStorage;
use Spryker\Zed\AvailabilityStorage\Business\Storage\AvailabilityStorageInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\AvailabilityStorage\AvailabilityStorageConfig getConfig()
 * @method \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface getQueryContainer()
 */
class AvailabilityStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityStorage\Business\Storage\AvailabilityStorageInterface
     */
    public function createAvailabilityStorage(): AvailabilityStorageInterface
    {
        return new AvailabilityStorage(
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
            $this->getRepository(),
        );
    }
}
