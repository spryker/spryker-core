<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
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
     * @deprecated Will be removed without replacement.
     *
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishByBusinessUnitProducts(array $businessUnitProducts): void
    {
        $this->publishByBusinessUnits(array_keys($businessUnitProducts));
    }

    /**
     * @param array $businessUnitIds
     *
     * @return void
     */
    public function publishByBusinessUnits(array $businessUnitIds): void
    {
        $productConcretes = $this->priceProductMerchantRelationshipStorageRepository
            ->getProductConcretePriceDataByCompanyBusinessUnitIds($businessUnitIds);

        $businessUnitsProductConcreteIds = [];
        foreach ($productConcretes as $productConcrete) {
            $businessUnitId = $productConcrete[MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT];
            $businessUnitsProductConcreteIds[$businessUnitId][] = $productConcrete[PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_CONCRETE_ID_PRODUCT];
        }

        foreach ($businessUnitsProductConcreteIds as $businessUnitId => $productConcreteIds) {
            $this->priceProductMerchantRelationshipStorageEntityManager
                ->cleanupPriceProductConcreteByCompanyBusinessUnit($businessUnitId, $productConcreteIds);
        }

        $this->write($productConcretes);
    }

    /**
     * @param array $productConcretes
     *
     * @return void
     */
    protected function write(array $productConcretes): void
    {
        $productsGroupedByIdCompanyBusinessUnit = $this->groupProductsByIdCompanyBusinessUnit($productConcretes);

        foreach ($productsGroupedByIdCompanyBusinessUnit as $idCompanyBusinessUnit => $productConcretes) {
            $groupedPrices = $this->priceGrouper->getGroupedPrices(
                $productConcretes,
                PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_CONCRETE_ID_PRODUCT,
                PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_CONCRETE_SKU
            );

            if (count($groupedPrices) === 0) {
                continue;
            }

            $productConcreteIds = array_column(
                $productConcretes,
                PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_CONCRETE_ID_PRODUCT
            );
            $priceProductMerchantRelationshipStorageEntityMap = $this->priceProductMerchantRelationshipStorageRepository
                ->findExistingPriceProductConcreteMerchantRelationshipStorageEntities($idCompanyBusinessUnit, $productConcreteIds);

            $this->priceProductMerchantRelationshipStorageEntityManager->writePriceProductConcrete(
                $groupedPrices,
                $priceProductMerchantRelationshipStorageEntityMap
            );
        }
    }

    /**
     * @param array $products
     *
     * @return array
     */
    protected function groupProductsByIdCompanyBusinessUnit(array $products): array
    {
        $productsGroupedByIdCompanyBusinessUnit = [];
        foreach ($products as $product) {
            $idCompanyBusinessUnit = $product[MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT];
            $productsGroupedByIdCompanyBusinessUnit[$idCompanyBusinessUnit][] = $product;
        }

        return $productsGroupedByIdCompanyBusinessUnit;
    }
}
