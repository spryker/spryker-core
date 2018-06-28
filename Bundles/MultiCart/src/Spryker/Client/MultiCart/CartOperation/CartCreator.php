<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\CartOperation;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToCustomerClientInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToPersistentCartClientInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToQuoteClientInterface;
use Spryker\Client\MultiCart\Dependency\Service\MultiCartToUtilDateTimeServiceInterface;
use Spryker\Client\MultiCart\MultiCartConfig;

class CartCreator implements CartCreatorInterface
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
     * @var \Spryker\Client\MultiCart\Dependency\Client\MultiCartToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\MultiCart\MultiCartConfig
     */
    protected $multiCartConfig;

    /**
     * @var \Spryker\Client\MultiCart\Dependency\Service\MultiCartToUtilDateTimeServiceInterface
     */
    protected $dateTimeService;

    /**
     * @param \Spryker\Client\MultiCart\Dependency\Client\MultiCartToPersistentCartClientInterface $persistentCartClient
     * @param \Spryker\Client\MultiCart\Dependency\Client\MultiCartToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\MultiCart\Dependency\Client\MultiCartToCustomerClientInterface $customerClient
     * @param \Spryker\Client\MultiCart\Dependency\Service\MultiCartToUtilDateTimeServiceInterface $dateTimeService
     * @param \Spryker\Client\MultiCart\MultiCartConfig $multiCartConfig
     */
    public function __construct(
        MultiCartToPersistentCartClientInterface $persistentCartClient,
        MultiCartToQuoteClientInterface $quoteClient,
        MultiCartToCustomerClientInterface $customerClient,
        MultiCartToUtilDateTimeServiceInterface $dateTimeService,
        MultiCartConfig $multiCartConfig
    ) {
        $this->persistentCartClient = $persistentCartClient;
        $this->quoteClient = $quoteClient;
        $this->customerClient = $customerClient;
        $this->multiCartConfig = $multiCartConfig;
        $this->dateTimeService = $dateTimeService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->setIdQuote(null);
        $quoteTransfer->setCustomer(
            $this->customerClient->getCustomer()
        );
        $quoteTransfer->setIsDefault(true);
        $quoteResponseTransfer = $this->persistentCartClient->createQuote($quoteTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function duplicateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = clone $quoteTransfer;
        $quoteTransfer->setName(
            sprintf($this->multiCartConfig->getDuplicatedQuoteName(), $quoteTransfer->getName(), $this->dateTimeService->formatDateTime(date('Y-m-d H:i:s')))
        );
        $quoteTransfer->setIdQuote(null);
        $quoteTransfer->setIsDefault(true);
        $quoteTransfer->setCustomer(
            $this->customerClient->getCustomer()
        );

        return $this->persistentCartClient->createQuote($quoteTransfer);
    }
}
