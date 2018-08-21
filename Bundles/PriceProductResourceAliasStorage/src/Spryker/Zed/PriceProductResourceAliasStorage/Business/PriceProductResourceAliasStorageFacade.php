<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceProductResourceAliasStorage\Business\PriceProductResourceAliasStorageBusinessFactory getFactory()
 */
class PriceProductResourceAliasStorageFacade extends AbstractFacade implements PriceProductResourceAliasStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function updatePriceProductAbstractStorageSkus(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createPriceProductAbstractStorageWriter()
            ->updatePriceProductAbstractStorageSkus($productAbstractIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return void
     */
    public function updatePriceProductConcreteStorageSkusByProductConcreteIds(array $productConcreteIds): void
    {
        $this->getFactory()
            ->createPriceProductConcreteStorageWriter()
            ->updatePriceProductConcreteStorageSkusByProductConcreteIds($productConcreteIds);
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
    public function updatePriceProductConcreteStorageSkusByStoreIds(array $priceProductStoreIds): void
    {
        $this->getFactory()
            ->createPriceProductConcreteStorageWriter()
            ->updatePriceProductConcreteStorageSkusByStoreIds($priceProductStoreIds);
    }
}
