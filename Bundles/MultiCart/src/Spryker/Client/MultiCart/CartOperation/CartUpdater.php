<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\CartOperation;

use ArrayObject;
use Generated\Shared\Transfer\QuoteActivationRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToCustomerClientInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToPersistentCartClientInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToQuoteClientInterface;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToZedRequestClientInterface;
use Spryker\Client\MultiCart\Zed\MultiCartZedStubInterface;

class CartUpdater implements CartUpdaterInterface
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
     * @var \Spryker\Client\MultiCart\Zed\MultiCartZedStubInterface
     */
    protected $multiCartZedStub;

    /**
     * @var \Spryker\Client\MultiCart\Dependency\Client\MultiCartToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\MultiCart\Zed\MultiCartZedStubInterface $multiCartZedStub
     * @param \Spryker\Client\MultiCart\Dependency\Client\MultiCartToPersistentCartClientInterface $persistentCartClient
     * @param \Spryker\Client\MultiCart\Dependency\Client\MultiCartToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\MultiCart\Dependency\Client\MultiCartToCustomerClientInterface $customerClient
     * @param \Spryker\Client\MultiCart\Dependency\Client\MultiCartToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(
        MultiCartZedStubInterface $multiCartZedStub,
        MultiCartToPersistentCartClientInterface $persistentCartClient,
        MultiCartToQuoteClientInterface $quoteClient,
        MultiCartToCustomerClientInterface $customerClient,
        MultiCartToZedRequestClientInterface $zedRequestClient
    ) {
        $this->multiCartZedStub = $multiCartZedStub;
        $this->persistentCartClient = $persistentCartClient;
        $this->quoteClient = $quoteClient;
        $this->customerClient = $customerClient;
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireIdQuote();
        $quoteTransfer->setCustomer(
            $this->customerClient->getCustomer()
        );
        $quoteUpdateRequestTransfer = $this->createQuoteUpdateRequest($quoteTransfer);
        $quoteUpdateRequestTransfer->getQuoteUpdateRequestAttributes()->fromArray($quoteTransfer->modifiedToArray(), true);
        $quoteResponseTransfer = $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setDefaultQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->setCustomer(
            $this->customerClient->getCustomer()
        );
        $quoteActivationRequestTransfer = new QuoteActivationRequestTransfer();
        $quoteActivationRequestTransfer->setCustomer($quoteTransfer->getCustomer());
        $quoteActivationRequestTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $quoteResponseTransfer = $this->multiCartZedStub->setDefaultQuote($quoteActivationRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }
        $this->zedRequestClient->addResponseMessagesToMessenger();

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function clearQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->setCustomer(
            $this->customerClient->getCustomer()
        );
        $quoteUpdateRequestTransfer = $this->createQuoteUpdateRequest($quoteTransfer);
        $quoteUpdateRequestTransfer->getQuoteUpdateRequestAttributes()
            ->setItems(new ArrayObject())
            ->setTotals(null)
            ->setExpenses(new ArrayObject());

        return $this->persistentCartClient->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    protected function createQuoteUpdateRequest(QuoteTransfer $quoteTransfer): QuoteUpdateRequestTransfer
    {
        $quoteUpdateRequestTransfer = new QuoteUpdateRequestTransfer();
        $quoteUpdateRequestTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $quoteUpdateRequestTransfer->setCustomer($quoteTransfer->getCustomer());
        $quoteUpdateRequestAttributesTransfer = new QuoteUpdateRequestAttributesTransfer();
        $quoteUpdateRequestTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);

        return $quoteUpdateRequestTransfer;
    }
}
