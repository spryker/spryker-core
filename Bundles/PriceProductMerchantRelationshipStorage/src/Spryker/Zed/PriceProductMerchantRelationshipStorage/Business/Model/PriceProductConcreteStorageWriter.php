<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepository;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface;

class PriceProductConcreteStorageWriter implements PriceProductConcreteStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface
     */
    protected $priceProductMerchantRelationshipStorageEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipRepositoryInterface
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
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishByBusinessUnitProducts(array $businessUnitProducts): void
    {
        foreach ($businessUnitProducts as $idCompanyBusinessUnit => $productIds) {
            foreach ($productIds as $idProduct) {
                $this->priceProductMerchantRelationshipStorageEntityManager
                    ->deletePriceProductConcreteByCompanyBusinessUnitAndIdProduct($idCompanyBusinessUnit, $idProduct);
            }
        }

        // re-publish remaining for BU prices
        $concreteProducts = $this->priceProductMerchantRelationshipStorageRepository
            ->findPriceProductStoresByCompanyBusinessUnitConcreteProducts($businessUnitProducts);

        $this->write($concreteProducts);
    }

    /**
     * @param array $priceProductStoreIds
     *
     * @return void
     */
    public function publishByPriceProductStoreIds(array $priceProductStoreIds): void
    {
        $concreteProducts = $this->priceProductMerchantRelationshipStorageRepository
            ->findPriceProductStoreListByIdsForConcrete($priceProductStoreIds);

        $this->write($concreteProducts);
    }

    /**
     * @param array $concreteProducts
     *
     * @return void
     */
    protected function write(array $concreteProducts): void
    {
        $groupedPrices = $this->priceGrouper->getGroupedPrices(
            $concreteProducts,
            PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_CONCRETE_ID_PRODUCT,
            PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_CONCRETE_SKU
        );

        if (count($groupedPrices) === 0) {
            return;
        }

        $priceProductMerchantRelationshipStorageEntityMap = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductConcreteMerchantRelationshipStorageEntities($concreteProducts);

        $this->priceProductMerchantRelationshipStorageEntityManager->writePriceProductConcrete(
            $groupedPrices,
            $priceProductMerchantRelationshipStorageEntityMap
        );
    }
}
