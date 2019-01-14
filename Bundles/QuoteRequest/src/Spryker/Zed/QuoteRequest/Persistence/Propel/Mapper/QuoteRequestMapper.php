<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\SpyQuoteRequestEntityTransfer;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest;

class QuoteRequestMapper implements QuoteRequestMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyQuoteRequestEntityTransfer[] $quoteRequestEntityTransferCollection
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function mapEntityCollectionToTransferCollection(
        array $quoteRequestEntityTransferCollection
    ): QuoteRequestCollectionTransfer {
        $quoteRequestItemCollectionTransfer = new QuoteRequestCollectionTransfer();

        foreach ($quoteRequestEntityTransferCollection as $itemEntityTransfer) {
            $quoteRequestItemTransfer = $this->mapQuoteRequestEntityToQuoteRequestTransfer(
                $itemEntityTransfer,
                new QuoteRequestTransfer()
            );
            $quoteRequestItemCollectionTransfer->addQuoteRequest($quoteRequestItemTransfer);
        }

        return $quoteRequestItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteRequestEntityTransfer $quoteRequestEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function mapQuoteRequestEntityToQuoteRequestTransfer(
        SpyQuoteRequestEntityTransfer $quoteRequestEntityTransfer,
        QuoteRequestTransfer $quoteRequestTransfer
    ): QuoteRequestTransfer {
        $quoteRequestTransfer = $quoteRequestTransfer->fromArray($quoteRequestEntityTransfer->modifiedToArray(), true);
        $quoteRequestTransfer->setCompanyUser(
            (new CompanyUserTransfer())->fromArray($quoteRequestEntityTransfer->getCompanyUser()->toArray(), true)
        );

        return $quoteRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest $quoteRequestEntity
     *
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest
     */
    public function mapQuoteRequestTransferToQuoteRequestEntity(
        QuoteRequestTransfer $quoteRequestTransfer,
        SpyQuoteRequest $quoteRequestEntity
    ): SpyQuoteRequest {
        $quoteRequestEntity->fromArray($quoteRequestTransfer->modifiedToArray());
        $quoteRequestEntity->setFkCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser());

        return $quoteRequestEntity;
    }
}
