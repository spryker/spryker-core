<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\CartDeleteChecker;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\PermissionAwareTrait;
use Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface;
use Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface;
use Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin;

class CartDeleteChecker implements CartDeleteCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface
     */
    protected $multiCartClient;

    /**
     * @var \Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\SharedCart\Dependency\Client\SharedCartToMultiCartClientInterface $multiCartClient
     * @param \Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface $customerClient
     */
    public function __construct(
        SharedCartToMultiCartClientInterface $multiCartClient,
        SharedCartToCustomerClientInterface $customerClient
    ) {
        $this->multiCartClient = $multiCartClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $currentQuote
     *
     * @return bool
     */
    public function isQuoteDeletable(QuoteTransfer $currentQuote): bool
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if (!$this->isQuoteOwner($currentQuote, $customerTransfer)) {
            return $this->can(WriteSharedCartPermissionPlugin::KEY, $currentQuote->getIdQuote());
        }
        foreach ($this->multiCartClient->getQuoteCollection()->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIdQuote() === $currentQuote->getIdQuote()) {
                continue;
            }

            if ($this->isQuoteOwner($quoteTransfer, $customerTransfer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isQuoteOwner(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        return strcmp($customerTransfer->getCustomerReference(), $quoteTransfer->getCustomerReference()) === 0;
    }
}
