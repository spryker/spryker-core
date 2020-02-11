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
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface getRepository()
 */
class PriceProductMerchantRelationshipStorageFacade extends AbstractFacade implements PriceProductMerchantRelationshipStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishConcretePriceProductByBusinessUnitProducts(array $businessUnitProducts): void
    {
        $this->getFactory()->createPriceProductConcreteStorageWriter()
            ->publishByBusinessUnitProducts($businessUnitProducts);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement
     *
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishAbstractPriceProductByBusinessUnitProducts(array $businessUnitProducts): void
    {
        $this->getFactory()->createPriceProductAbstractStorageWriter()
            ->publishByBusinessUnitProducts($businessUnitProducts);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishAbstractPriceProductByBusinessUnits(array $companyBusinessUnitIds): void
    {
        $this->getFactory()->createPriceProductAbstractStorageWriter()
            ->publishByCompanyBusinessUnitIds($companyBusinessUnitIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishConcretePriceProductByBusinessUnits(array $companyBusinessUnitIds): void
    {
        $this->getFactory()->createPriceProductConcreteStorageWriter()
            ->publishByCompanyBusinessUnitIds($companyBusinessUnitIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function publishAbstractPriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void
    {
        $this->getFactory()->createPriceProductAbstractStorageWriter()
            ->publishAbstractPriceProductMerchantRelationship($priceProductMerchantRelationshipIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishAbstractPriceProductByProductAbstractIds(array $productAbstractIds): void
    {
        $this->getFactory()->createPriceProductAbstractStorageWriter()
            ->publishAbstractPriceProductByProductAbstractIds($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $priceProductMerchantRelationshipIds
     *
     * @return void
     */
    public function publishConcretePriceProductMerchantRelationship(array $priceProductMerchantRelationshipIds): void
    {
        $this->getFactory()->createPriceProductConcreteStorageWriter()
            ->publishConcretePriceProductMerchantRelationship($priceProductMerchantRelationshipIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishConcretePriceProductByProductIds(array $productIds): void
    {
        $this->getFactory()->createPriceProductConcreteStorageWriter()
            ->publishConcretePriceProductByProductIds($productIds);
    }
}
