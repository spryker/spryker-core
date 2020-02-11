<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Business\MerchantOpeningHoursStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageRepositoryInterface getRepository()
 */
class MerchantOpeningHoursStorageFacade extends AbstractFacade implements MerchantOpeningHoursStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $merchantIds
     *
     * @return void
     */
    public function publish(array $merchantIds): void
    {
        $this->getFactory()
            ->createMerchantOpeningHoursStoragePublisher()
            ->publish($merchantIds);
    }
}
