<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Quote\Persistence\SpyQuote;
use Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceInterface;
use Spryker\Zed\Quote\QuoteConfig;

class QuoteMapper implements QuoteMapperInterface
{
    /**
     * @var \Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * @var \Spryker\Zed\Quote\QuoteConfig
     */
    protected $quoteConfig;

    /**
     * QuoteMapper constructor.
     *
     * @param \Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceInterface $encodingService
     * @param \Spryker\Zed\Quote\QuoteConfig $quoteConfig
     */
    public function __construct(
        QuoteToUtilEncodingServiceInterface $encodingService,
        QuoteConfig $quoteConfig
    ) {
        $this->encodingService = $encodingService;
        $this->quoteConfig = $quoteConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteEntityTransfer $quoteEntityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapQuoteTransfer(SpyQuoteEntityTransfer $quoteEntityTransfer): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->fromArray($quoteEntityTransfer->modifiedToArray(), true);
        $quoteTransfer->fromArray($this->decodeQuoteData($quoteEntityTransfer), true);
        $storeTransfer = new StoreTransfer();
        $storeTransfer->fromArray($quoteEntityTransfer->getSpyStore()->modifiedToArray(), true);
        $quoteTransfer->setStore($storeTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Quote\Persistence\SpyQuote $quoteEntity
     *
     * @return \Orm\Zed\Quote\Persistence\SpyQuote
     */
    public function mapTransferToEntity(QuoteTransfer $quoteTransfer, SpyQuote $quoteEntity): SpyQuote
    {
        $quoteEntity->fromArray($quoteTransfer->modifiedToArray());

        if ($quoteEntity->isNew()) {
            $quoteEntity
                ->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference())
                ->setFkStore($quoteTransfer->getStore()->getIdStore());
        }
        $quoteEntity->setQuoteData($this->encodeQuoteData($quoteTransfer));

        return $quoteEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteEntityTransfer $quoteEntityTransfer
     *
     * @return array
     */
    protected function decodeQuoteData(SpyQuoteEntityTransfer $quoteEntityTransfer)
    {
        return $this->encodingService->decodeJson($quoteEntityTransfer->getQuoteData(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function encodeQuoteData(QuoteTransfer $quoteTransfer)
    {
        $quoteData = $this->filterDisallowedQuoteData($quoteTransfer);

        return $this->encodingService->encodeJson($quoteData, JSON_OBJECT_AS_ARRAY);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function filterDisallowedQuoteData(QuoteTransfer $quoteTransfer)
    {
        $data = [];
        $quoteData = $quoteTransfer->modifiedToArray(true, true);
        foreach ($this->quoteConfig->getQuoteFieldsAllowedForSaving() as $dataKey) {
            if (isset($quoteData[$dataKey])) {
                $data[$dataKey] = $quoteData[$dataKey];
            }
        }

        return $data;
    }
}
