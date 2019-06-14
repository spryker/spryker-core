<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\ProductListQueryExpander;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Spryker\Zed\MerchantRelationshipProductListGui\Persistence\MerchantRelationshipProductListGuiRepositoryInterface;

class ProductListQueryExpander implements ProductListQueryExpanderInterface
{
    /**
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
                MerchantRelationshipProductListGuiRepositoryInterface::COL_MERCHANT_NAME => MerchantRelationshipProductListGuiRepositoryInterface::COL_MERCHANT_NAME_ALIAS,
                MerchantRelationshipProductListGuiRepositoryInterface::COL_COMPANY_BUSINESS_UNIT_NAME => MerchantRelationshipProductListGuiRepositoryInterface::COL_BUSINESS_UNIT_OWNER_NAME_ALIAS,
            ]);
    }

    /**
     * @module ProductList
     * @module MerchantRelationship
     *
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createMerchantRelationshipJoin(): QueryJoinTransfer
    {
        return (new QueryJoinTransfer())
            ->setLeft([MerchantRelationshipProductListGuiRepositoryInterface::COL_FK_MERCHANT_RELATIONSHIP])
            ->setRight([MerchantRelationshipProductListGuiRepositoryInterface::COL_ID_MERCHANT_RELATIONSHIP])
            ->setJoinType(MerchantRelationshipProductListGuiRepositoryInterface::LEFT_JOIN);
    }

    /**
     * @module MerchantRelationship
     * @module Merchant
     *
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createMerchantJoin(): QueryJoinTransfer
    {
        return (new QueryJoinTransfer())
            ->setLeft([MerchantRelationshipProductListGuiRepositoryInterface::COL_FK_MERCHANT])
            ->setRight([MerchantRelationshipProductListGuiRepositoryInterface::COL_ID_MERCHANT])
            ->setJoinType(MerchantRelationshipProductListGuiRepositoryInterface::LEFT_JOIN);
    }

    /**
     * @module CompanyBusinessUnit
     * @module MerchantRelationship
     *
     * @return \Generated\Shared\Transfer\QueryJoinTransfer
     */
    protected function createCompanyBusinessUnitJoin(): QueryJoinTransfer
    {
        return (new QueryJoinTransfer())
            ->setLeft([MerchantRelationshipProductListGuiRepositoryInterface::COL_FK_COMPANY_BUSINESS_UNIT])
            ->setRight([MerchantRelationshipProductListGuiRepositoryInterface::COL_ID_COMPANY_BUSINESS_UNIT])
            ->setJoinType(MerchantRelationshipProductListGuiRepositoryInterface::LEFT_JOIN);
    }
}
