<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityResourceAliasStorage\Business;

use Spryker\Zed\AvailabilityResourceAliasStorage\Business\ProductAvailabilityStorage\AvailabilityWriter;
use Spryker\Zed\AvailabilityResourceAliasStorage\Business\ProductAvailabilityStorage\AvailabilityWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AvailabilityResourceAliasStorage\AvailabilityResourceAliasStorageConfig getConfig()
 * @method \Spryker\Zed\AvailabilityResourceAliasStorage\Business\AvailabilityResourceAliasStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\AvailabilityResourceAliasStorage\Persistence\AvailabilityResourceAliasStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AvailabilityResourceAliasStorage\Persistence\AvailabilityResourceAliasStorageRepositoryInterface getRepository()
 */
class AvailabilityResourceAliasStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AvailabilityResourceAliasStorage\Business\ProductAvailabilityStorage\AvailabilityWriterInterface
     */
    public function createAvailabilityStorageWriter(): AvailabilityWriterInterface
    {
        return new AvailabilityWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
