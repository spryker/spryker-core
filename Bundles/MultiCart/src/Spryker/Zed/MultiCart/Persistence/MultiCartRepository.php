<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function resolveCustomerQuoteName(QuoteTransfer $quoteTransfer): string
    {
        if (!$this->findQuoteByName($quoteTransfer)) {
            return $quoteTransfer->getName();
        }

        $customerReference = $quoteTransfer->getCustomer()->getCustomerReference();
        if ($quoteTransfer->getCustomerReference()) {
            $customerReference = $quoteTransfer->getCustomerReference();
        }
        $quoteQuery = $this->getFactory()
            ->createQuoteQuery()
            ->filterByName($quoteTransfer->getName() . '%', Criteria::LIKE)
            ->filterByCustomerReference($customerReference);
        if ($quoteTransfer->getIdQuote()) {
            $quoteQuery->filterByIdQuote($quoteTransfer->getIdQuote(), Criteria::NOT_EQUAL);
        }

        $quoteEntityTransferCollection = $this->buildQueryFromCriteria($quoteQuery)->find();
        if ($quoteEntityTransferCollection) {
            return $quoteTransfer->getName() . ' ' . $this->findBiggestQuoteSuffix($quoteEntityTransferCollection);
        }

        return $quoteTransfer->getName();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteEntityTransfer[] $quoteEntityTransferCollection
     *
     * @return int
     */
    protected function findBiggestQuoteSuffix(array $quoteEntityTransferCollection): int
    {
        $lastQuoteSuffix = 1;
        foreach ($quoteEntityTransferCollection as $quoteTransfer) {
            preg_match_all('/^.+ (\d+)$/', $quoteTransfer->getName(), $matches, PREG_SET_ORDER);
            if (isset($matches[0][1]) && $lastQuoteSuffix <= (int)$matches[0][1]) {
                $lastQuoteSuffix = (int)$matches[0][1] + 1;
            }
        }

        return $lastQuoteSuffix;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return null|\Generated\Shared\Transfer\SpyQuoteEntityTransfer
     */
    protected function findQuoteByName(QuoteTransfer $quoteTransfer): ?SpyQuoteEntityTransfer
    {
        $customerReference = $quoteTransfer->getCustomer()->getCustomerReference();
        if ($quoteTransfer->getCustomerReference()) {
            $customerReference = $quoteTransfer->getCustomerReference();
        }
        $quoteQuery = $this->getFactory()
            ->createQuoteQuery()
            ->filterByName($quoteTransfer->getName())
            ->filterByCustomerReference($customerReference);
        if ($quoteTransfer->getIdQuote()) {
            $quoteQuery->filterByIdQuote($quoteTransfer->getIdQuote(), Criteria::NOT_EQUAL);
        }
        $filterTransfer = new FilterTransfer();
        $filterTransfer->setOrderBy(SpyQuoteTableMap::COL_NAME);
        $filterTransfer->setOrderDirection(Criteria::DESC);

        return $this->buildQueryFromCriteria($quoteQuery, $filterTransfer)->findOne();
    }
}
