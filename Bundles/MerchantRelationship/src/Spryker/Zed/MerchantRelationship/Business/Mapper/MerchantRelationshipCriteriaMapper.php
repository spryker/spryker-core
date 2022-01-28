<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Mapper;

use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortCollectionTransfer;
use Generated\Shared\Transfer\SortTransfer;

class MerchantRelationshipCriteriaMapper implements MerchantRelationshipCriteriaMapperInterface
{
    /**
     * @uses \Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria::ASC
     *
     * @var string
     */
    protected const SORT_ASCENDING = 'ASC';

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    public function mapMerchantRelationshipFilterToMerchantRelationshipCriteria(
        MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer,
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): MerchantRelationshipCriteriaTransfer {
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->setMerchantRelationshipIds($merchantRelationshipFilterTransfer->getMerchantRelationshipIds());

        $paginationTransfer = (new PaginationTransfer())
            ->setFirstIndex($merchantRelationshipFilterTransfer->getOffset())
            ->setMaxPerPage($merchantRelationshipFilterTransfer->getLimit());

        $sortCollectionTransfer = new SortCollectionTransfer();
        foreach ($merchantRelationshipFilterTransfer->getSortBy() as $column => $direction) {
            $sortTransfer = (new SortTransfer())
                ->setField($column)
                ->setIsAscending($direction === static::SORT_ASCENDING);
            $sortCollectionTransfer->addSort($sortTransfer);
        }

        return $merchantRelationshipCriteriaTransfer->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer)->setPagination($paginationTransfer)->setSortCollection($sortCollectionTransfer);
    }
}
