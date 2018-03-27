<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SpyQuoteEntityTransfer;
use Orm\Zed\Quote\Persistence\Map\SpyQuoteTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MultiCart\Persistence\MultiCartPersistenceFactory getFactory()
 */
class MultiCartRepository extends AbstractRepository implements MultiCartRepositoryInterface
{
    /**
     * @param string $quoteName
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\SpyQuoteEntityTransfer
     */
    public function findCustomerQuoteByName(string $quoteName, string $customerReference): SpyQuoteEntityTransfer
    {
        $quoteQuery = $this->getFactory()
            ->createQuoteQuery()
            ->filterByName($quoteName . '%', Criteria::LIKE)
            ->filterByCustomerReference($customerReference);

        $filterTransfer = new FilterTransfer();
        $filterTransfer->setOrderBy(SpyQuoteTableMap::COL_NAME);
        $filterTransfer->setOrderDirection(Criteria::DESC);

        return $this->buildQueryFromCriteria($quoteQuery, $filterTransfer)->findOne();
    }
}
