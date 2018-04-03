<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Quote\Persistence\Map\SpyQuoteTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MultiCart\Persistence\MultiCartPersistenceFactory getFactory()
 */
class MultiCartRepository extends AbstractRepository implements MultiCartRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return null|\Generated\Shared\Transfer\SpyQuoteEntityTransfer
     */
    public function resolveCustomerQuoteName(QuoteTransfer $quoteTransfer): string
    {
        $customerReference = $quoteTransfer->getCustomer()->getCustomerReference();
        $quoteQuery = $this->getFactory()
            ->createQuoteQuery()
            ->filterByName($quoteTransfer->getName() . '%', Criteria::LIKE)
            ->filterByCustomerReference($customerReference);
        if ($quoteTransfer->getIdQuote()) {
            $quoteQuery->filterByIdQuote($quoteTransfer->getIdQuote(), Criteria::NOT_EQUAL);
        }

        $filterTransfer = new FilterTransfer();
        $filterTransfer->setOrderBy(SpyQuoteTableMap::COL_NAME);
        $filterTransfer->setOrderDirection(Criteria::DESC);

        $quoteByNameTransfer = $this->buildQueryFromCriteria($quoteQuery, $filterTransfer)->findOne();
        if ($quoteByNameTransfer) {
            preg_match_all('/^.+ (\d+)$/', $quoteByNameTransfer->getName(), $matches, PREG_SET_ORDER);
            $lastQuoteSuffix = 1;
            if ($matches) {
                $lastQuoteSuffix += (int)$matches[0][1];
            }

            return $quoteTransfer->getName() . ' ' . $lastQuoteSuffix;
        }

        return $quoteTransfer->getName();
    }
}
