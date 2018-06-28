<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Persistence\Propel\PriceDimensionQueryExpander;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\QueryConditionTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipToCompanyBusinessUnitTableMap;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\Map\SpyPriceProductMerchantRelationshipTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

class MerchantRelationshipPriceQueryExpander implements MerchantRelationshipPriceQueryExpanderInterface
{
    /**
     * @uses \Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap::COL_ID_PRICE_PRODUCT_STORE
     */
    public const COL_ID_PRICE_PRODUCT_STORE = 'spy_price_product_store.id_price_product_store';

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer|null
     */
    public function buildMerchantRelationshipPriceDimensionQueryCriteria(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?QueryCriteriaTransfer
    {
        if (!$priceProductCriteriaTransfer->getPriceDimension()) {
            return $this->createQueryCriteriaTransfer();
        }

        $idMerchantRelationship = null;
        if ($priceProductCriteriaTransfer->getPriceDimension()) {
            $idMerchantRelationship = $priceProductCriteriaTransfer->getPriceDimension()->getIdMerchantRelationship();
        }

        if ($idMerchantRelationship) {
            return $this->createQueryCriteriaTransfer($idMerchantRelationship);
        }

        $idCompanyBusinessUnit = null;
        if ($priceProductCriteriaTransfer->getPriceDimension()) {
            $idCompanyBusinessUnit = $priceProductCriteriaTransfer->getPriceDimension()->getIdCompanyBusinessUnit();
        }

        if (!$idCompanyBusinessUnit) {
            $idCompanyBusinessUnit = $this->findIdCompanyBusinessUnit($priceProductCriteriaTransfer);
        }

        if (!$idCompanyBusinessUnit) {
            return null;
        }

        return $this->createQueryCriteriaTransfer($idMerchantRelationship, $idCompanyBusinessUnit);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return int|null
     */
    protected function findIdCompanyBusinessUnit(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?int
    {
        if (!$priceProductCriteriaTransfer->getQuote()) {
            return null;
        }

        $customerTransfer = $priceProductCriteriaTransfer->getQuote()->getCustomer();
        if (!$customerTransfer) {
            return null;
        }

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if (!$companyUserTransfer) {
            return null;
        }

        $businessUnit = $companyUserTransfer->getCompanyBusinessUnit();
        if (!$businessUnit) {
            return null;
        }

        return $businessUnit->getIdCompanyBusinessUnit();
    }

    /**
     * @param int|null $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createJoin(?int $idMerchantRelationship = null): QueryJoinTransfer
    {
        $left[] = static::COL_ID_PRICE_PRODUCT_STORE;
        $right[] = SpyPriceProductMerchantRelationshipTableMap::COL_FK_PRICE_PRODUCT_STORE;

        if ($idMerchantRelationship) {
            $left[] = SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP;
            $right[] = $idMerchantRelationship;
        }

        return (new QueryJoinTransfer())
            ->setLeft($left)
            ->setRight($right)
            ->setJoinType(Criteria::LEFT_JOIN);
    }

    /**
     * @param int|null $idMerchantRelationship
     * @param int|null $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    protected function createQueryCriteriaTransfer(?int $idMerchantRelationship = null, ?int $idCompanyBusinessUnit = null): QueryCriteriaTransfer
    {
        $queryCriteriaTransfer = (new QueryCriteriaTransfer())
            ->addJoin(
                $this->createJoin($idMerchantRelationship)
            )
            ->setWithColumns([
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP => PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP,
            ]);

        if ($idCompanyBusinessUnit) {
            $queryCriteriaTransfer
                ->addCondition(
                    (new QueryConditionTransfer())
                        ->setName('')
                        ->setColumn(SpyMerchantRelationshipToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT)
                        ->setValue($idCompanyBusinessUnit)
                        ->setComparison('=')
                );
        }

        return $queryCriteriaTransfer;
    }
}
