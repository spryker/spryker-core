<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\PriceProductMerchantRelationshipStorageBusinessFactory getFactory()
 */
class PriceProductMerchantRelationshipStorageFacade extends AbstractFacade implements PriceProductMerchantRelationshipStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $priceProductStoreIds
     *
     * @return void
     */
    public function publishConcretePriceProduct(array $priceProductStoreIds): void
    {
        $this->getFactory()->createPriceProductConcreteStorageWriter()->publish($priceProductStoreIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $priceProductStoreIds
     *
     * @return void
     */
    public function publishAbstractPriceProduct(array $priceProductStoreIds): void
    {
        $this->getFactory()->createPriceProductAbstractStorageWriter()->publish($priceProductStoreIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $merchantRelationshipConcreteProducts
     *
     * @return void
     */
    public function unpublishConcretePriceProduct(array $merchantRelationshipConcreteProducts): void
    {
        $this->getFactory()->createPriceProductConcreteStorageWriter()->unpublish($merchantRelationshipConcreteProducts);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $merchantRelationshipAbstractProducts
     *
     * @return void
     */
    public function unpublishAbstractPriceProduct(array $merchantRelationshipAbstractProducts): void
    {
        $this->getFactory()->createPriceProductAbstractStorageWriter()->unpublish($merchantRelationshipAbstractProducts);
    }
}
