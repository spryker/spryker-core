<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityResourceAliasStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AvailabilityResourceAliasStorage\Business\AvailabilityResourceAliasStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\AvailabilityResourceAliasStorage\Persistence\AvailabilityResourceAliasStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\AvailabilityResourceAliasStorage\Persistence\AvailabilityResourceAliasStorageEntityManagerInterface getEntityManager()
 */
class AvailabilityResourceAliasStorageFacade extends AbstractFacade implements AvailabilityResourceAliasStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $availabilityIds
     *
     * @return void
     */
    public function updateAvailabilityStorageSkus(array $availabilityIds): void
    {
        $this->getFactory()
            ->createAvailabilityStorageWriter()
            ->updateAvailabilityStorageSkus($availabilityIds);
    }
}
