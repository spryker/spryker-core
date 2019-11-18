<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\Business\MerchantProductOfferStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface getEntityManager()
 */
class MerchantProductOfferStorageFacade extends AbstractFacade implements MerchantProductOfferStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $productSkus
     *
     * @return void
     */
    public function publishProductConcreteProductOffersStorage(array $productSkus): void
    {
        $this->getFactory()->createProductConcreteProductOffersStorageWriter()->publish($productSkus);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $productSkus
     *
     * @return void
     */
    public function unpublishProductConcreteProductOffersStorage(array $productSkus): void
    {
        $this->getFactory()->createProductConcreteProductOffersStorageWriter()->unpublish($productSkus);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function publishProductOfferStorage(array $productOfferReferences): void
    {
        $this->getFactory()->createProductOfferStorageWriter()->publish($productOfferReferences);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function unpublishProductOfferStorage(array $productOfferReferences): void
    {
        $this->getFactory()->createProductOfferStorageWriter()->unpublish($productOfferReferences);
    }
}
