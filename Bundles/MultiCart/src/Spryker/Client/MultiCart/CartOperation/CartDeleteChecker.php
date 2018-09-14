<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\CartOperation;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\MultiCart\Storage\MultiCartStorageInterface;

class CartDeleteChecker implements CartDeleteCheckerInterface
{
    /**
     * @var \Spryker\Client\MultiCart\Storage\MultiCartStorageInterface
     */
    protected $multiCartStorage;

    /**
     * @param \Spryker\Client\MultiCart\Storage\MultiCartStorageInterface $multiCartStorage
     */
    public function __construct(MultiCartStorageInterface $multiCartStorage)
    {
        $this->multiCartStorage = $multiCartStorage;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $currentQuoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function isDeleteCartAllowed(QuoteTransfer $currentQuoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        $ownedQuoteNumber = 0;
        foreach ($this->multiCartStorage->getQuoteCollection()->getQuotes() as $quoteTransfer) {
            if ($this->isQuoteOwner($quoteTransfer, $customerTransfer)) {
                $ownedQuoteNumber++;
            }
        }

        return $ownedQuoteNumber > 1 || (!$this->isQuoteOwner($currentQuoteTransfer, $customerTransfer) && $ownedQuoteNumber > 0);
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
