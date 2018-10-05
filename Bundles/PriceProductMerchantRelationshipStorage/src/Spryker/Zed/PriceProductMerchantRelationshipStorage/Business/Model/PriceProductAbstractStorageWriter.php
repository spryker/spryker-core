<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepository;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface;

class PriceProductAbstractStorageWriter implements PriceProductAbstractStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface
     */
    protected $priceProductMerchantRelationshipStorageEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface
     */
    protected $priceProductMerchantRelationshipStorageRepository;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface
     */
    protected $priceGrouper;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface $priceProductMerchantRelationshipStorageEntityManager
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface $priceProductMerchantRelationshipStorageRepository
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface $priceGrouper
     */
    public function __construct(
        PriceProductMerchantRelationshipStorageEntityManagerInterface $priceProductMerchantRelationshipStorageEntityManager,
        PriceProductMerchantRelationshipStorageRepositoryInterface $priceProductMerchantRelationshipStorageRepository,
        PriceGrouperInterface $priceGrouper
    ) {
        $this->priceProductMerchantRelationshipStorageEntityManager = $priceProductMerchantRelationshipStorageEntityManager;
        $this->priceProductMerchantRelationshipStorageRepository = $priceProductMerchantRelationshipStorageRepository;
        $this->priceGrouper = $priceGrouper;
    }

    /**
     * @param array $priceProductStoreIds
     *
     * @return void
     */
    public function publishByPriceProductStoreIds(array $priceProductStoreIds): void
    {
        $abstractProducts = $this->priceProductMerchantRelationshipStorageRepository
            ->findPriceProductStoreListByIdsForAbstract($priceProductStoreIds);

        $this->write($abstractProducts);
    }

    /**
     * @param array $businessUnitIds
     *
     * @return void
     */
    public function publishByBusinessUnits(array $businessUnitIds): void
    {
        foreach ($businessUnitIds as $idCompanyBusinessUnit) {
            $this->priceProductMerchantRelationshipStorageEntityManager
                ->deletePriceProductAbstractByCompanyBusinessUnit($idCompanyBusinessUnit);
        }

        // re-publish remaining prices
        $abstractProducts = $this->priceProductMerchantRelationshipStorageRepository
            ->findPriceProductStoresByCompanyBusinessUnitAbstractProducts($businessUnitIds);

        $this->write($abstractProducts);
    }

    /**
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishByBusinessUnitProducts(array $businessUnitProducts): void
    {
        foreach ($businessUnitProducts as $idCompanyBusinessUnit => $productAbstractIds) {
            foreach ($productAbstractIds as $idProductAbstract) {
                $this->priceProductMerchantRelationshipStorageEntityManager
                    ->deletePriceProductAbstractByCompanyBusinessUnitAndIdProductAbstract($idCompanyBusinessUnit, $idProductAbstract);
            }
        }

        // re-publish remaining prices
        $abstractProducts = $this->priceProductMerchantRelationshipStorageRepository
            ->findPriceProductStoresByCompanyBusinessUnitAbstractProducts(array_keys($businessUnitProducts));

        $this->write($abstractProducts);
    }

    /**
     * @param array $abstractProducts
     *
     * @return void
     */
    protected function write(array $abstractProducts): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceGrouper->getGroupedPrices(
            $abstractProducts,
            PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_ABSTRACT_ID_PRODUCT,
            PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_ABSTRACT_SKU
        );

        if (count($priceProductMerchantRelationshipStorageTransfers) === 0) {
            return;
        }

        $priceProductMerchantRelationshipStorageEntityMap = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductAbstractMerchantRelationshipStorageEntities($abstractProducts);

        $this->priceProductMerchantRelationshipStorageEntityManager->writePriceProductAbstract(
            $priceProductMerchantRelationshipStorageTransfers,
            $priceProductMerchantRelationshipStorageEntityMap
        );
    }
}
