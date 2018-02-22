<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\StorageStrategy;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface;
use Spryker\Shared\Quote\QuoteConfig;

class DatabaseStorageStrategy implements StorageStrategyInterface
{
    /**
     * @var \Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\Quote\Dependency\Client\QuoteToCustomerClientInterface $customerClient
     */
    public function __construct(QuoteToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @return string
     */
    public function getStorageType()
    {
        return QuoteConfig::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        return $this->customerClient->isLoggedIn();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        return new QuoteTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return $this
     */
    public function setQuote(QuoteTransfer $quoteTransfer)
    {
        return $this;
    }

    /**
     * @return $this
     */
    public function clearQuote()
    {
        return $this;
    }
}
