<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Persistence\Propel\PriceDimensionQueryExpander;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
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
        $idMerchantRelationship = null;
        if ($priceProductCriteriaTransfer->getPriceDimension()) {
            $idMerchantRelationship = $priceProductCriteriaTransfer->getPriceDimension()->getIdMerchantRelationship();
        }

        if ($idMerchantRelationship) {
            return $this->createQueryCriteriaTransfer([$idMerchantRelationship]);
        }

        $merchantRelationshipIds = $this->findMerchantRelationshipIds($priceProductCriteriaTransfer);
        if (!$merchantRelationshipIds) {
            return null;
        }

        return $this->createQueryCriteriaTransfer($merchantRelationshipIds);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array
     */
    protected function findMerchantRelationshipIds(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array
    {
        if (!$priceProductCriteriaTransfer->getQuote()) {
            return [];
        }

        $customerTransfer = $priceProductCriteriaTransfer->getQuote()->getCustomer();
        if (!$customerTransfer) {
            return [];
        }

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if (!$companyUserTransfer) {
            return [];
        }

        $businessUnit = $companyUserTransfer->getCompanyBusinessUnit();
        if (!$businessUnit) {
            return [];
        }

        $merchantRelationshipIds = [];
        foreach ($businessUnit->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $merchantRelationshipIds[] = $merchantRelationshipTransfer->getIdMerchantRelationship();
        }

        return $merchantRelationshipIds;
    }

    /**
     * @param int[] $merchantRelationshipIds
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    protected function createQueryCriteriaTransfer(array $merchantRelationshipIds): QueryCriteriaTransfer
    {
        return (new QueryCriteriaTransfer())
            ->setWithColumns([
                SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP => PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP,
            ])
            ->addJoin(
                (new QueryJoinTransfer())
                    ->setRelation('PriceProductMerchantRelationship')
                    ->setCondition(SpyPriceProductMerchantRelationshipTableMap::COL_FK_MERCHANT_RELATIONSHIP
                        . ' IN (' . implode(',', $merchantRelationshipIds) . ')')
                    ->setJoinType(Criteria::LEFT_JOIN)
            );
    }
}
