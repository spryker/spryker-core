<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageRepositoryInterface getRepository()
 */
class MerchantProfileStorageFacade extends AbstractFacade implements MerchantProfileStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $merchantProfileIds
     *
     * @return void
     */
    public function publish(array $merchantProfileIds): void
    {
        $this->getFactory()->createMerchantProfileStorageWriter()->publish($merchantProfileIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $merchantProfileIds
     *
     * @return void
     */
    public function unpublish(array $merchantProfileIds): void
    {
        $this->getFactory()->createMerchantProfileStorageWriter()->unpublish($merchantProfileIds);
    }
}
