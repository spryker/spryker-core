<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Plugin\Checkout;

use DateTime;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Client\CheckoutExtension\Dependency\Plugin\CheckoutPreCheckPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig;

/**
 * @method \Spryker\Client\QuoteRequest\QuoteRequestClient getClient()
 */
class QuoteRequestCheckoutPreCheckPlugin extends AbstractPlugin implements CheckoutPreCheckPluginInterface
{
    /**
     * {@inheritdoc}
     * - Returns true if quote does't have quote request version reference.
     * - Returns true if related quote request is still valid.
     * - Returns false othervise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function isValid(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        /**
         * @todo Need to use QuoteRequestChecker::checkValidUntil and get rid of outdated QuoteRequestPreCheckPlugin
         */
        if (!$quoteTransfer->getQuoteRequestVersionReference()) {
            return (new QuoteValidationResponseTransfer())
                ->setIsSuccessful(true);
        }

        $quoteRequestVersionTransfers = $this->getClient()->getQuoteRequestVersionCollectionByFilter(
            (new QuoteRequestVersionFilterTransfer())
                ->setQuoteRequestVersionReference($quoteTransfer->getQuoteRequestVersionReference())
        )
            ->getQuoteRequestVersions()
            ->getArrayCopy();

        $quoteRequestVersionTransfer = array_shift($quoteRequestVersionTransfers);
        $quoteRequest = $quoteRequestVersionTransfer->getQuoteRequest();

        return (new QuoteValidationResponseTransfer())
            ->setIsSuccessful(
                new DateTime($quoteRequest->getValidUntil()) > new DateTime()
                && $quoteRequest->getStatus() === QuoteRequestConfig::STATUS_READY
            );
    }
}
