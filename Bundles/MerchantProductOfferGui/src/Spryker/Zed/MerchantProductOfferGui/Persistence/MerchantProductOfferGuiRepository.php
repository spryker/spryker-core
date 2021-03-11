<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferGui\Persistence;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOfferGui\Persistence\MerchantProductOfferGuiPersistenceFactory getFactory()
 */
class MerchantProductOfferGuiRepository extends AbstractRepository implements MerchantProductOfferGuiRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteriaTransfer(
        QueryCriteriaTransfer $queryCriteriaTransfer,
        MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
    ): QueryCriteriaTransfer {
        $queryJoinTransfer = (new QueryJoinTransfer())->setRelation('SpyMerchant');

        if ($merchantProductOfferCriteriaTransfer->getMerchantReference()) {
            $queryJoinTransfer->setJoinType(Criteria::INNER_JOIN)
                ->setCondition(sprintf('%s = \'%s\'', SpyProductOfferTableMap::COL_MERCHANT_REFERENCE, $merchantProductOfferCriteriaTransfer->getMerchantReference()));
        } else {
            $queryJoinTransfer->setJoinType(Criteria::LEFT_JOIN);
        }

        $queryCriteriaTransfer->addJoin($queryJoinTransfer)
            ->setWithColumns([SpyMerchantTableMap::COL_NAME => MerchantTransfer::NAME]);

        return $queryCriteriaTransfer;
    }
}
