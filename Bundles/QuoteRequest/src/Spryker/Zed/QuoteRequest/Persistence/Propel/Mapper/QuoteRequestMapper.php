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
use Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface;

class QuoteRequestMapper implements QuoteRequestMapperInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * @param \Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface $encodingService
     */
    public function __construct(QuoteRequestToUtilEncodingServiceInterface $encodingService)
    {
        $this->encodingService = $encodingService;
    }

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

        $quoteRequestTransfer->setMetadata($this->decodeMetadata($quoteRequestEntityTransfer));
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
        $data = $quoteRequestTransfer->modifiedToArray();
        unset($data['metadata']);

        $quoteRequestEntity->fromArray($data);

        $quoteRequestEntity->setFkCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser());
        $quoteRequestEntity->setMetadata($this->encodeMetadata($quoteRequestTransfer));

        return $quoteRequestEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteRequestEntityTransfer $quoteRequestEntityTransfer
     *
     * @return array
     */
    protected function decodeMetadata(SpyQuoteRequestEntityTransfer $quoteRequestEntityTransfer): array
    {
        return $this->encodingService->decodeJson($quoteRequestEntityTransfer->getMetadata(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return string
     */
    protected function encodeMetadata(QuoteRequestTransfer $quoteRequestTransfer): string
    {
        return $this->encodingService->encodeJson($quoteRequestTransfer->getMetadata(), JSON_OBJECT_AS_ARRAY);
    }
}
