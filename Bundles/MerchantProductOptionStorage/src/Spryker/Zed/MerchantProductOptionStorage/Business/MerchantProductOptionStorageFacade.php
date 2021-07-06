<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProductOptionStorage\Business\MerchantProductOptionStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOptionStorage\Persistence\MerchantProductOptionStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOptionStorage\Persistence\MerchantProductOptionStorageEntityManagerInterface getEntityManager()
 */
class MerchantProductOptionStorageFacade extends AbstractFacade implements MerchantProductOptionStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantProductOptionGroupEvents(array $eventTransfers): void
    {
         $this->getFactory()
            ->createMerchantProductOptionStorageWriter()
            ->writeCollectionByMerchantProductOptionGroupEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $productOptionTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    public function filterProductOptions(array $productOptionTransfers): array
    {
        return $this->getFactory()
            ->createMerchantProductOptionFilter()
            ->filterProductOptions($productOptionTransfers);
    }
}
