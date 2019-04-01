<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface;
use Spryker\Zed\QuoteRequest\QuoteRequestConfig;

class QuoteRequestVersionMapper
{
    /**
     * @var \Spryker\Zed\QuoteRequest\QuoteRequestConfig
     */
    protected $quoteRequestConfig;

    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * @param \Spryker\Zed\QuoteRequest\QuoteRequestConfig $quoteRequestConfig
     * @param \Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface $encodingService
     */
    public function __construct(
        QuoteRequestConfig $quoteRequestConfig,
        QuoteRequestToUtilEncodingServiceInterface $encodingService
    ) {
        $this->quoteRequestConfig = $quoteRequestConfig;
        $this->encodingService = $encodingService;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $quoteRequestEntities
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer
     */
    public function mapEntityCollectionToTransferCollection(
        Collection $quoteRequestEntities
    ): QuoteRequestVersionCollectionTransfer {
        $quoteRequestVersionCollectionTransfer = new QuoteRequestVersionCollectionTransfer();

        foreach ($quoteRequestEntities as $quoteRequestEntity) {
            $quoteRequestVersionTransfer = $this->mapQuoteRequestVersionEntityToQuoteRequestVersionTransfer(
                $quoteRequestEntity,
                new QuoteRequestVersionTransfer()
            );
            $quoteRequestVersionCollectionTransfer->addQuoteRequestVersion($quoteRequestVersionTransfer);
        }

        return $quoteRequestVersionCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion $quoteRequestVersion
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function mapQuoteRequestVersionEntityToQuoteRequestVersionTransfer(
        SpyQuoteRequestVersion $quoteRequestVersion,
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer
    ): QuoteRequestVersionTransfer {
        $quoteRequestVersionTransfer = $quoteRequestVersionTransfer
            ->fromArray($quoteRequestVersion->toArray(), true)
            ->setMetadata($this->decodeMetadata($quoteRequestVersion))
            ->setQuoteRequest($this->getQuoteRequest($quoteRequestVersion));

        if ($quoteRequestVersion->getQuote()) {
            $quoteRequestVersionTransfer->setQuote($this->decodeQuote($quoteRequestVersion->getQuote()));
        }

        return $quoteRequestVersionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion $quoteRequestVersionEntity
     *
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion
     */
    public function mapQuoteRequestVersionTransferToQuoteRequestVersionEntity(
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer,
        SpyQuoteRequestVersion $quoteRequestVersionEntity
    ): SpyQuoteRequestVersion {
        $data = $quoteRequestVersionTransfer->modifiedToArray();
        unset($data['quote']);
        unset($data['metadata']);

        $quoteRequestVersionEntity->fromArray($data);

        $quoteRequestVersionEntity
            ->setQuote($this->encodeQuoteData($quoteRequestVersionTransfer->getQuote()))
            ->setMetadata($this->encodeMetadata($quoteRequestVersionTransfer));

        return $quoteRequestVersionEntity;
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion $quoteRequestVersion
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function getQuoteRequest(SpyQuoteRequestVersion $quoteRequestVersion): QuoteRequestTransfer
    {
        $quoteRequestEntity = $quoteRequestVersion->getSpyQuoteRequest();
        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->fromArray($quoteRequestEntity->toArray(), true)
            ->setCompanyUser(
                (new CompanyUserTransfer())->setIdCompanyUser($quoteRequestEntity->getFkCompanyUser())
            );

        return $quoteRequestTransfer;
    }

    /**
     * @param string $encodedQuoteData
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function decodeQuote(string $encodedQuoteData): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->fromArray($this->decodeQuoteData($encodedQuoteData), true);
    }

    /**
     * @param string $data
     *
     * @return array
     */
    protected function decodeQuoteData(string $data): array
    {
        return $this->encodingService->decodeJson($data, true);
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion $quoteRequestVersionEntity
     *
     * @return array
     */
    protected function decodeMetadata(SpyQuoteRequestVersion $quoteRequestVersionEntity): array
    {
        return $this->encodingService->decodeJson($quoteRequestVersionEntity->getMetadata(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return string
     */
    protected function encodeMetadata(QuoteRequestVersionTransfer $quoteRequestVersionTransfer): string
    {
        return $this->encodingService->encodeJson($quoteRequestVersionTransfer->getMetadata(), JSON_OBJECT_AS_ARRAY);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function encodeQuoteData(QuoteTransfer $quoteTransfer): string
    {
        $quoteData = $this->filterDisallowedQuoteData($quoteTransfer);

        return $this->encodingService->encodeJson($quoteData, JSON_OBJECT_AS_ARRAY);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function filterDisallowedQuoteData(QuoteTransfer $quoteTransfer): array
    {
        $data = [];
        $quoteData = $quoteTransfer->modifiedToArray(true, true);

        foreach ($this->quoteRequestConfig->getQuoteFieldsAllowedForSaving() as $dataKey) {
            if (isset($quoteData[$dataKey])) {
                $data[$dataKey] = $quoteData[$dataKey];
            }
        }

        return $data;
    }
}
