<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence;

use DateTime;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteEntityTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Quote\Persistence\Map\SpyQuoteTableMap;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Quote\Persistence\QuotePersistenceFactory getFactory()
 */
class QuoteRepository extends AbstractRepository implements QuoteRepositoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @deprecated Use findQuoteByCustomerReferenceAndIdStore() instead.
     *
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
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
     * {@inheritDoc}
     *
     * @param string $customerReference
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteByCustomerReferenceAndIdStore(string $customerReference, int $idStore): ?QuoteTransfer
    {
        $quoteQuery = $this->getFactory()->createQuoteQuery()
            ->joinWithSpyStore()
            ->filterByCustomerReference($customerReference)
            ->filterByFkStore($idStore);

        $quoteEntity = $this->buildQueryFromCriteria($quoteQuery)->findOne();
        if (!$quoteEntity) {
            return null;
        }

        return $this->getFactory()->createQuoteMapper()->mapQuoteTransfer($quoteEntity);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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

        $quoteQuery = $this->applyQuoteCriteriaFilters($quoteQuery, $quoteCriteriaFilterTransfer);
        $quoteEntityCollectionTransfer = $this->buildQueryFromCriteria($quoteQuery, $quoteCriteriaFilterTransfer->getFilter())->find();

        $quoteCollectionTransfer = new QuoteCollectionTransfer();
        $quoteMapper = $this->getFactory()->createQuoteMapper();
        foreach ($quoteEntityCollectionTransfer as $quoteEntityTransfer) {
            $quoteCollectionTransfer->addQuote($quoteMapper->mapQuoteTransfer($quoteEntityTransfer));
        }

        return $quoteCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Quote\Persistence\SpyQuoteQuery $quoteQuery
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Orm\Zed\Quote\Persistence\SpyQuoteQuery
     */
    protected function applyQuoteCriteriaFilters(SpyQuoteQuery $quoteQuery, QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): SpyQuoteQuery
    {
        if ($quoteCriteriaFilterTransfer->getCustomerReference()) {
            $quoteQuery->filterByCustomerReference($quoteCriteriaFilterTransfer->getCustomerReference());
        }

        if ($quoteCriteriaFilterTransfer->isPropertyModified(QuoteCriteriaFilterTransfer::QUOTE_IDS)) {
            $quoteQuery->filterByIdQuote_In($quoteCriteriaFilterTransfer->getQuoteIds());
        }

        if ($quoteCriteriaFilterTransfer->getIdStore()) {
            $quoteQuery->filterByFkStore($quoteCriteriaFilterTransfer->getIdStore());
        }

        return $quoteQuery;
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

    /**
     * @param \DateTime $lifetimeLimitDate
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function findExpiredGuestQuotes(DateTime $lifetimeLimitDate, int $limit): QuoteCollectionTransfer
    {
        $quoteQuery = $this->getFactory()
            ->createQuoteQuery()
            ->joinWithSpyStore()
            ->addJoin(SpyQuoteTableMap::COL_CUSTOMER_REFERENCE, SpyCustomerTableMap::COL_CUSTOMER_REFERENCE, Criteria::LEFT_JOIN)
            ->filterByUpdatedAt(['max' => $lifetimeLimitDate], Criteria::LESS_EQUAL)
            ->where(SpyCustomerTableMap::COL_CUSTOMER_REFERENCE . Criteria::ISNULL)
            ->orderByUpdatedAt()
            ->limit($limit);

        $quoteEntityCollectionTransfer = $this->buildQueryFromCriteria($quoteQuery)->find();

        $quoteMapper = $this->getFactory()->createQuoteMapper();
        $quoteCollectionTransfer = new QuoteCollectionTransfer();
        foreach ($quoteEntityCollectionTransfer as $quoteEntityTransfer) {
            $quoteCollectionTransfer->addQuote($quoteMapper->mapQuoteTransfer($quoteEntityTransfer));
        }

        return $quoteCollectionTransfer;
    }

    /**
     * @param string $uuidQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteByUuid(string $uuidQuote): ?QuoteTransfer
    {
        $quoteQuery = $this->getFactory()
            ->createQuoteQuery()
            ->joinWithSpyStore()
            ->filterByUuid($uuidQuote);

        $quoteEntityTransfer = $this->buildQueryFromCriteria($quoteQuery)->findOne();
        if (!$quoteEntityTransfer) {
            return null;
        }

        return $this->getFactory()->createQuoteMapper()->mapQuoteTransfer($quoteEntityTransfer);
    }
}
