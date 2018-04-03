<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Quote\Persistence\QuotePersistenceFactory getFactory()
 */
class QuoteRepository extends AbstractRepository implements QuoteRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return null|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function findQuoteByCustomer($customerReference): ?QuoteTransfer
    {
        $quoteQuery = $this->getFactory()->createQuoteQuery()
            ->joinWithSpyStore()
            ->filterByCustomerReference($customerReference);

        $quoteEntityTransfer = $this->buildQueryFromCriteria($quoteQuery)->findOne();
        if (!$quoteEntityTransfer) {
            return null;
        }

        return $this->getFactory()->createQuoteMapper()->mapQuoteTransfer($quoteEntityTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteById($idQuote): ?QuoteTransfer
    {
        $quoteQuery = $this->getFactory()->createQuoteQuery()
            ->joinWithSpyStore()
            ->filterByIdQuote($idQuote);

        $quoteEntityTransfer = $this->buildQueryFromCriteria($quoteQuery)->findOne();
        if (!$quoteEntityTransfer) {
            return null;
        }

        return $this->getFactory()->createQuoteMapper()->mapQuoteTransfer($quoteEntityTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function filterQuoteCollection(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        $quoteQuery = $this->getFactory()
            ->createQuoteQuery()
            ->joinWithSpyStore();

        if ($quoteCriteriaFilterTransfer->getCustomerReference()) {
            $quoteQuery->filterByCustomerReference($quoteCriteriaFilterTransfer->getCustomerReference());
        }

        $quoteEntityCollectionTransfer = $this->buildQueryFromCriteria($quoteQuery, $quoteCriteriaFilterTransfer->getFilter())->find();

        $quoteCollectionTransfer = new QuoteCollectionTransfer();
        $quoteMapper = $this->getFactory()->createQuoteMapper();
        foreach ($quoteEntityCollectionTransfer as $quoteEntityTransfer) {
            $quoteCollectionTransfer->addQuote($quoteMapper->mapQuoteTransfer($quoteEntityTransfer));
        }

        return $quoteCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteEntityTransfer $quoteEntityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapQuoteTransfer(SpyQuoteEntityTransfer $quoteEntityTransfer): QuoteTransfer
    {
        return $this->getFactory()->createQuoteMapper()->mapQuoteTransfer($quoteEntityTransfer);
    }
}
