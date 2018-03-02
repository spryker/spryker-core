<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Persistence\Propel;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteEntityTransfer;
use Orm\Zed\Quote\Persistence\SpyQuote;
use Spryker\Zed\Quote\Dependency\Service\QuoteToUtilEncodingServiceInterface;

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
     */
    public function mapQuoteTransfer(SpyQuoteEntityTransfer $quoteEntityTransfer): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->fromArray($this->decodeQuoteData($quoteEntityTransfer));
        $quoteTransfer->setIdQuote($quoteEntityTransfer->getIdQuote());
        // TODO: all data that was filtered out need to be set back for the result

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Quote\Persistence\SpyQuote
     */
    public function mapTransferToEntity(QuoteTransfer $quoteTransfer): SpyQuote
    {
        $quoteEntity = new SpyQuote();
        $quoteEntity->setNew(!$quoteTransfer->getIdQuote());
        $quoteEntity->setIdQuote($quoteTransfer->getIdQuote());
        $quoteEntity->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());
        $quoteEntity->setFkStore($quoteTransfer->getStore()->getIdStore());
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
