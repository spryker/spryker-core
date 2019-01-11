<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteEntityTransfer;
use Generated\Shared\Transfer\SpyQuoteRequestVersionEntityTransfer;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion;
use Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface;
use Spryker\Zed\QuoteRequest\QuoteRequestConfig;

class QuoteRequestVersionMapper implements QuoteRequestVersionMapperInterface
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
     * @param \Generated\Shared\Transfer\SpyQuoteRequestVersionEntityTransfer $quoteRequestVersionEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function mapQuoteRequestVersionEntityToQuoteRequestVersionTransfer(
        SpyQuoteRequestVersionEntityTransfer $quoteRequestVersionEntityTransfer,
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer
    ): QuoteRequestVersionTransfer {
        $quoteRequestVersionTransfer = $quoteRequestVersionTransfer
            ->fromArray($quoteRequestVersionEntityTransfer->modifiedToArray(), true);

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
        unset($data['original_quote']);

        $quoteRequestVersionEntity->fromArray($data);

        $quoteRequestVersionEntity->setQuote(
            $this->encodeQuoteData($quoteRequestVersionTransfer->getQuote())
        );
        $quoteRequestVersionEntity->setOriginalQuote(
            $this->encodeQuoteData($quoteRequestVersionTransfer->getOriginalQuote())
        );

        return $quoteRequestVersionEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteEntityTransfer $quoteEntityTransfer
     *
     * @return array
     */
    protected function decodeQuoteData(SpyQuoteEntityTransfer $quoteEntityTransfer): array
    {
        return $this->encodingService->decodeJson($quoteEntityTransfer->getQuoteData(), true);
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
