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

class QuoteMapper implements QuoteMapperInterface
{
    /**
     * @var \Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * @param \Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceInterface $encodingService
     */
    public function __construct(QuoteToUtilEncodingServiceInterface $encodingService)
    {
        $this->encodingService = $encodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteEntityTransfer $quoteEntityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapQuoteTransfer(SpyQuoteEntityTransfer $quoteEntityTransfer): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteData = array_merge($quoteEntityTransfer->modifiedToArray(), $this->decodeQuoteData($quoteEntityTransfer));
        $quoteTransfer->fromArray($quoteData, true);
        $storeTransfer = new StoreTransfer();
        $storeTransfer->fromArray($quoteEntityTransfer->getSpyStore()->modifiedToArray(), true);
        $quoteTransfer->setStore($storeTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Quote\Persistence\SpyQuote $quoteEntity
     * @param string[] $quoteFieldsAllowedForSaving
     *
     * @return \Orm\Zed\Quote\Persistence\SpyQuote
     */
    public function mapTransferToEntity(QuoteTransfer $quoteTransfer, SpyQuote $quoteEntity, array $quoteFieldsAllowedForSaving): SpyQuote
    {
        $quoteEntity->fromArray($quoteTransfer->modifiedToArray());

        if ($quoteEntity->isNew()) {
            $quoteEntity
                ->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference())
                ->setFkStore($quoteTransfer->getStore()->getIdStore());
        }
        $quoteEntity->setQuoteData($this->encodeQuoteData($quoteTransfer, $quoteFieldsAllowedForSaving));

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
     * @param string[] $quoteFieldsAllowedForSaving
     *
     * @return string
     */
    protected function encodeQuoteData(QuoteTransfer $quoteTransfer, array $quoteFieldsAllowedForSaving)
    {
        $quoteData = $this->filterDisallowedQuoteData(
            $quoteTransfer->modifiedToArray(true, true),
            $quoteFieldsAllowedForSaving
        );

        return $this->encodingService->encodeJson($quoteData, JSON_OBJECT_AS_ARRAY);
    }

    /**
     * @param array $quoteData
     * @param array $quoteFieldsAllowedForSaving
     *
     * @return array
     */
    protected function filterDisallowedQuoteData(array $quoteData, array $quoteFieldsAllowedForSaving): array
    {
        $data = [];
        foreach ($quoteFieldsAllowedForSaving as $fieldKey => $fieldData) {
            if (is_string($fieldData) && isset($quoteData[$fieldData])) {
                $data[$fieldData] = $quoteData[$fieldData];

                continue;
            }

            if (is_array($fieldData) && isset($quoteData[$fieldKey])) {
                foreach ($quoteData[$fieldKey] as $itemData) {
                    $data[$fieldKey][] = $this->filterDisallowedQuoteData($itemData, $fieldData);
                }
            }
        }

        return $data;
    }
}
