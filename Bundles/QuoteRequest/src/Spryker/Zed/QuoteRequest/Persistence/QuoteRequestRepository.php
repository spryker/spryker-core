<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestPersistenceFactory getFactory()
 */
class QuoteRequestRepository extends AbstractRepository implements QuoteRequestRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestCollectionTransfer
    {
        $quoteRequestQuery = $this->getFactory()
            ->getQuoteRequestPropelQuery()
            ->orderByIdQuoteRequest(Criteria::DESC)
            ->filterByFkCompanyUser($quoteRequestFilterTransfer->getCompanyUser()->getIdCompanyUser())
            ->leftJoinWithSpyQuoteRequestVersion();

        if ($quoteRequestFilterTransfer->getQuoteRequestReference()) {
            $quoteRequestQuery->filterByQuoteRequestReference($quoteRequestFilterTransfer->getQuoteRequestReference());
        }

        return $this->getFactory()
            ->createQuoteRequestMapper()
            ->mapEntityCollectionToTransferCollection($quoteRequestQuery->find());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): ?QuoteRequestTransfer
    {
        $quoteRequestEntity = $this->getFactory()
            ->getQuoteRequestPropelQuery()
            ->filterByFkCompanyUser($quoteRequestFilterTransfer->getCompanyUser()->getIdCompanyUser())
            ->filterByQuoteRequestReference($quoteRequestFilterTransfer->getQuoteRequestReference())
            ->leftJoinWithSpyQuoteRequestVersion()
            ->findOne();

        if (!$quoteRequestEntity) {
            return null;
        }

        return $this->getFactory()
            ->createQuoteRequestMapper()
            ->mapQuoteRequestEntityToQuoteRequestTransfer($quoteRequestEntity, new QuoteRequestTransfer());
    }
}
