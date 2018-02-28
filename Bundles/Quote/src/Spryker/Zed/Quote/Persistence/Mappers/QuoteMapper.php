<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence\Mappers;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteEntityTransfer;
use Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceInterface;

// TODO: move this class to Propel namespace
class QuoteMapper implements QuoteMapperInterface
{
    /**
     * @var \Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * QuoteMapper constructor.
     *
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
     * TODO: method name could be `mapQuoteTransfer()`
     */
    public function mapEntityTransferToTransfer(SpyQuoteEntityTransfer $quoteEntityTransfer): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->fromArray($this->restoreQuoteData($quoteEntityTransfer));
        $quoteTransfer->setIdQuote($quoteEntityTransfer->getIdQuote());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpyQuoteEntityTransfer
     */
    public function mapTransferToEntityTransfer(QuoteTransfer $quoteTransfer): SpyQuoteEntityTransfer
    {
        $quoteEntityTransfer = new SpyQuoteEntityTransfer();
        $quoteEntityTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $quoteEntityTransfer->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());
        $quoteEntityTransfer->setFkStore($quoteTransfer->getStore()->getIdStore());
        $quoteEntityTransfer->setQuoteData($this->extractQuoteData($quoteTransfer));

        return $quoteEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteEntityTransfer $quoteEntityTransfer
     *
     * @return array
     * TODO: rename to `decodeQuoteData()`
     */
    protected function restoreQuoteData(SpyQuoteEntityTransfer $quoteEntityTransfer)
    {
        return $this->encodingService->decodeJson($quoteEntityTransfer->getQuoteData(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     * TODO: rename to `encodeQuoteData()`
     */
    protected function extractQuoteData(QuoteTransfer $quoteTransfer)
    {
        $quoteData = $quoteTransfer->modifiedToArray();
        $quoteData = $this->clearCheckoutQuoteData($quoteData);

        return $this->encodingService->encodeJson($quoteData);
    }

    /**
     * @param array $quoteData
     *
     * @return array
     */
    protected function clearCheckoutQuoteData(array $quoteData)
    {
        unset(
            $quoteData['customer'],
            $quoteData['billing_address'],
            $quoteData['shipping_address'],
            $quoteData['billing_same_as_shipping'],
            $quoteData['checkout_confirmed'],
            $quoteData['shipment'],
            $quoteData['payment'],
            $quoteData['payments'],
            $quoteData['cart_rule_discounts'],
            $quoteData['expenses']
        );
        return $quoteData;
    }
}
