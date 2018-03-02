<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;

class CartOperation implements CartOperationInterface
{
    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        PersistentCartToCartFacadeInterface $cartFacade,
        PersistentCartToQuoteFacadeInterface $quoteFacade
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function add(PersistentCartChangeTransfer $persistentCartChangeTransfer)
    {
        $persistentCartChangeTransfer->requireCustomer();
        $quoteTransfer = $this->getCustomerQuote($persistentCartChangeTransfer->getCustomer());
        $cartChangeTransfer = $this->createCartChangeTransfer($quoteTransfer);
        $cartChangeTransfer->setItems($persistentCartChangeTransfer->getItems());
        $quoteTransfer = $this->cartFacade->add($cartChangeTransfer);
        $this->quoteFacade->persistQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function remove(PersistentCartChangeTransfer $persistentCartChangeTransfer)
    {
        $persistentCartChangeTransfer->requireCustomer();
        $quoteTransfer = $this->getCustomerQuote($persistentCartChangeTransfer->getCustomer());
        $cartChangeTransfer = $this->createCartChangeTransfer($quoteTransfer);
        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            $persistentCartChangeTransfer->addItem($this->findItemInQuote($itemTransfer, $quoteTransfer));
        }
        $quoteTransfer = $this->cartFacade->remove($cartChangeTransfer);
        $this->quoteFacade->persistQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->requireCustomer();
        $quoteTransfer = $this->getCustomerQuote($quoteTransfer->getCustomer());
        $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
        $this->quoteFacade->persistQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return null|\Generated\Shared\Transfer\ItemTransfer
     */
    protected function findItemInQuote(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): ?ItemTransfer
    {
        foreach ($quoteTransfer->getItems() as $quoteItemTransfer) {
            if ($quoteItemTransfer->getSku() === $itemTransfer->getSku()
                && $quoteItemTransfer->getGroupKey() === $itemTransfer->getGroupKey()
            ) {
                return $quoteItemTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getCustomerQuote(CustomerTransfer $customerTransfer)
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteResponseTransfer = $this->quoteFacade->findQuoteByCustomer($customerTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        }
        $quoteTransfer->setCustomer($customerTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer(QuoteTransfer $quoteTransfer)
    {
        $items = $quoteTransfer->getItems();

        if (count($items) === 0) {
            $quoteTransfer->setItems(new ArrayObject());
        }

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }
}
