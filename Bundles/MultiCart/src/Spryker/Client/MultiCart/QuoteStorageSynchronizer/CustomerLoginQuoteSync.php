<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\QuoteStorageSynchronizer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToPersistentCartClientInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToQuoteClientInterface;
use Spryker\Shared\Quote\QuoteConfig;

class CustomerLoginQuoteSync implements CustomerLoginQuoteSyncInterface
{
    /**
     * @var \Spryker\Client\MultiCart\Dependency\Client\MultiCartToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @var \Spryker\Client\MultiCart\Dependency\Client\MultiCartToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\MultiCart\Dependency\Client\MultiCartToPersistentCartClientInterface $persistentCartClient
     * @param \Spryker\Client\MultiCart\Dependency\Client\MultiCartToQuoteClientInterface $quoteClient
     */
    public function __construct(
        MultiCartToPersistentCartClientInterface $persistentCartClient,
        MultiCartToQuoteClientInterface $quoteClient
    ) {
        $this->persistentCartClient = $persistentCartClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function syncQuoteForCustomer(CustomerTransfer $customerTransfer)
    {
        if ($this->quoteClient->getStorageStrategy() !== QuoteConfig::STORAGE_STRATEGY_DATABASE) {
            return;
        }

        $quoteTransfer = $this->quoteClient->getQuote();
        if ($quoteTransfer->getCustomerReference() || !count($quoteTransfer->getItems())) {
            $quoteTransfer->setIsDefault(true);
            $this->quoteClient->setQuote($quoteTransfer);

            return;
        }

        $quoteTransfer
            ->setCustomer($customerTransfer)
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setIsDefault(true);

        $this->persistentCartClient->createQuote($quoteTransfer);
    }
}
