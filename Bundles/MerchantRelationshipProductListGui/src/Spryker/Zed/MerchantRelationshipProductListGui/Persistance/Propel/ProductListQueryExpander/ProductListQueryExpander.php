<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Persistance\Propel\ProductListQueryExpander;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\MerchantRelationshipProductListGui\Persistance\MerchantRelationshipProductListGuiRepositoryInterface;

class ProductListQueryExpander implements ProductListQueryExpanderInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildProductListMerchantQueryCriteria(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer
    {
        return $queryCriteriaTransfer
            ->addJoin($this->createMerchantRelationshipJoin())
            ->addJoin($this->createMerchantJoin())
            ->addJoin($this->createCompanyBusinessUnitJoin())
            ->setWithColumns([
                SpyMerchantTableMap::COL_NAME => MerchantRelationshipProductListGuiRepositoryInterface::COL_MERCHANT_NAME_ALIAS,
                SpyCompanyBusinessUnitTableMap::COL_NAME => MerchantRelationshipProductListGuiRepositoryInterface::COL_BUSINESS_UNIT_OWNER_NAME_ALIAS,
            ]);
    }

    /**
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createMerchantRelationshipJoin(): QueryJoinTransfer
    {
        return (new QueryJoinTransfer())
            ->setLeft([SpyProductListTableMap::COL_FK_MERCHANT_RELATIONSHIP])
            ->setRight([SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP])
            ->setJoinType(Criteria::LEFT_JOIN);
    }

    /**
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createMerchantJoin(): QueryJoinTransfer
    {
        return (new QueryJoinTransfer())
            ->setLeft([SpyMerchantRelationshipTableMap::COL_FK_MERCHANT])
            ->setRight([SpyMerchantTableMap::COL_ID_MERCHANT])
            ->setJoinType(Criteria::LEFT_JOIN);
    }

    /**
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createCompanyBusinessUnitJoin(): QueryJoinTransfer
    {
        return (new QueryJoinTransfer())
            ->setLeft([SpyMerchantRelationshipTableMap::COL_FK_COMPANY_BUSINESS_UNIT])
            ->setRight([SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT])
            ->setJoinType(Criteria::LEFT_JOIN);
    }
}
