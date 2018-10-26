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
     * @param array $businessUnitIds
     *
     * @return void
     */
    public function publishByBusinessUnits(array $businessUnitIds): void
    {
        $productAbstracts = $this->priceProductMerchantRelationshipStorageRepository
            ->getProductAbstractPriceDataByCompanyBusinessUnitIds($businessUnitIds);

        $businessUnitsProductAbstractIds = [];
        foreach ($productAbstracts as $productAbstract) {
            $businessUnitId = $productAbstract[MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT];
            $businessUnitsProductAbstractIds[$businessUnitId][] = $productAbstract[PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_ABSTRACT_ID_PRODUCT];
        }

        foreach ($businessUnitsProductAbstractIds as $businessUnitId => $productAbstractIds) {
            $this->priceProductMerchantRelationshipStorageEntityManager
                ->cleanupPriceProductAbstractByCompanyBusinessUnit($businessUnitId, $productAbstractIds);
        }

        $this->write($productAbstracts);
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
     * @param array $productAbstracts
     *
     * @return void
     */
    protected function write(array $productAbstracts): void
    {
        $productsGroupedByIdCompanyBusinessUnit = $this->groupProductsByIdCompanyBusinessUnit($productAbstracts);

        foreach ($productsGroupedByIdCompanyBusinessUnit as $idCompanyBusinessUnit => $productAbstracts) {
            $priceProductMerchantRelationshipStorageTransfers = $this->priceGrouper->getGroupedPrices(
                $productAbstracts,
                PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_ABSTRACT_ID_PRODUCT,
                PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_ABSTRACT_SKU
            );

            if (count($priceProductMerchantRelationshipStorageTransfers) === 0) {
                continue;
            }

            $productAbstractIds = array_column(
                $productAbstracts,
                PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_ABSTRACT_ID_PRODUCT
            );
            $priceProductMerchantRelationshipStorageEntityMap = $this->priceProductMerchantRelationshipStorageRepository
                ->findExistingPriceProductAbstractMerchantRelationshipStorageEntities($idCompanyBusinessUnit, $productAbstractIds);

            $this->priceProductMerchantRelationshipStorageEntityManager->writePriceProductAbstract(
                $priceProductMerchantRelationshipStorageTransfers,
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
