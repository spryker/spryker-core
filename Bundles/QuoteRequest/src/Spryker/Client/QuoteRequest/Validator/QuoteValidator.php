<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Validator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\PermissionAwareTrait;

class QuoteValidator implements QuoteValidatorInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Client\QuoteRequestExtension\Dependency\Plugin\QuoteRequestQuoteCheckPluginInterface[]
     */
    protected $quoteRequestQuoteCheckPlugins;

    /**
     * @param array $quoteRequestQuoteCheckPlugins
     */
    public function __construct(array $quoteRequestQuoteCheckPlugins)
    {
        $this->quoteRequestQuoteCheckPlugins = $quoteRequestQuoteCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApplicableForQuoteRequest(QuoteTransfer $quoteTransfer): bool
    {
        if (!$quoteTransfer->getCustomer() || !$quoteTransfer->getCustomer()->getCompanyUserTransfer()) {
            return false;
        }

        if ($quoteTransfer->getQuoteRequestVersionReference() || $quoteTransfer->getQuoteRequestReference()) {
            return false;
        }

        if (!$this->canWriteQuote($quoteTransfer)) {
            return false;
        }

        return $this->executeQuoteRequestQuoteCheckPlugins($quoteTransfer);
    }

    /**
     * @uses \Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function canWriteQuote(QuoteTransfer $quoteTransfer): bool
    {
        // owner of cart
        if ($quoteTransfer->getCustomerReference() === $quoteTransfer->getCustomer()->getCustomerReference()) {
            return true;
        }

        // cart is shared with the customer
        if ($this->can('WriteSharedCartPermissionPlugin', $quoteTransfer->getIdQuote())) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function executeQuoteRequestQuoteCheckPlugins(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($this->quoteRequestQuoteCheckPlugins as $quoteRequestQuoteCheckPlugin) {
            if (!$quoteRequestQuoteCheckPlugin->check($quoteTransfer)) {
                return false;
            }
        }

        return true;
    }
}
