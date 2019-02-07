<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestPersistenceFactory getFactory()
 */
class QuoteRequestRepository extends AbstractRepository implements QuoteRequestRepositoryInterface
{
    /**
     * @module Customer
     * @module CompanyUser
     *
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(
        QuoteRequestFilterTransfer $quoteRequestFilterTransfer
    ): QuoteRequestCollectionTransfer {
        $quoteRequestQuery = $this->getFactory()
            ->getQuoteRequestPropelQuery()
            ->leftJoinWithSpyQuoteRequestVersion()
            ->useCompanyUserQuery()
                ->joinWithCustomer()
            ->endUse()
            ->orderByIdQuoteRequest(Criteria::DESC);

        if ($quoteRequestFilterTransfer->getCompanyUser() && $quoteRequestFilterTransfer->getCompanyUser()->getIdCompanyUser()) {
            $quoteRequestQuery->filterByFkCompanyUser($quoteRequestFilterTransfer->getCompanyUser()->getIdCompanyUser());
        }

        if ($quoteRequestFilterTransfer->getQuoteRequestReference()) {
            $quoteRequestQuery->filterByQuoteRequestReference($quoteRequestFilterTransfer->getQuoteRequestReference());
        }

        return $this->getFactory()
            ->createQuoteRequestMapper()
            ->mapEntityCollectionToTransferCollection($quoteRequestQuery->find());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer
     */
    public function getQuoteRequestVersionCollectionByFilter(QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer): QuoteRequestVersionCollectionTransfer
    {
        $quoteRequestVersionQuery = $this->getFactory()
            ->getQuoteRequestVersionPropelQuery()
            ->leftJoinSpyQuoteRequest()
            ->orderByIdQuoteRequestVersion(Criteria::DESC);

        if ($quoteRequestVersionFilterTransfer->getQuoteRequest() && $quoteRequestVersionFilterTransfer->getQuoteRequest()->getIdQuoteRequest()) {
            $quoteRequestVersionQuery->filterByFkQuoteRequest($quoteRequestVersionFilterTransfer->getQuoteRequest()->getIdQuoteRequest());
        }

        if ($quoteRequestVersionFilterTransfer->getQuoteRequestVersionReference()) {
            $quoteRequestVersionQuery->filterByVersionReference($quoteRequestVersionFilterTransfer->getQuoteRequestVersionReference());
        }

        return $this->getFactory()
            ->createQuoteRequestVersionMapper()
            ->mapEntityCollectionToTransferCollection($quoteRequestVersionQuery->find());
    }
}
