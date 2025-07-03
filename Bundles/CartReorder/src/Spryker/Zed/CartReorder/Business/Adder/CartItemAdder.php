<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorder\Business\Adder;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Shared\Kernel\StrategyResolverInterface;
use Spryker\Zed\CartReorder\Dependency\Facade\CartReorderToCartFacadeInterface;

class CartItemAdder implements CartItemAdderInterface
{
    /**
     * @param \Spryker\Zed\CartReorder\Dependency\Facade\CartReorderToCartFacadeInterface $cartFacade
     * @param \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderPreAddToCartPluginInterface>> $cartReorderPreAddToCartPluginStrategyResolver
     */
    public function __construct(
        protected CartReorderToCartFacadeInterface $cartFacade,
        protected StrategyResolverInterface $cartReorderPreAddToCartPluginStrategyResolver
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function addToCart(CartReorderTransfer $cartReorderTransfer): CartReorderResponseTransfer
    {
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setItems($this->sanitizeSalesOrderItemIds($cartReorderTransfer->getReorderItems()))
            ->setQuote($cartReorderTransfer->getQuoteOrFail());

        $cartChangeTransfer = $this->executeCartReorderPreAddToCartPlugins($cartChangeTransfer);
        $quoteResponseTransfer = $this->cartFacade->addToCart($cartChangeTransfer);

        $cartReorderTransfer->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $this->mapQuoteResponseTransferToCartReorderResponseTransfer(
            $quoteResponseTransfer,
            new CartReorderResponseTransfer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    protected function mapQuoteResponseTransferToCartReorderResponseTransfer(
        QuoteResponseTransfer $quoteResponseTransfer,
        CartReorderResponseTransfer $cartReorderResponseTransfer
    ): CartReorderResponseTransfer {
        $cartReorderResponseTransfer->setQuote($quoteResponseTransfer->getQuoteTransfer());

        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $cartReorderResponseTransfer->addError(
                (new ErrorTransfer())->fromArray($quoteErrorTransfer->toArray(), true),
            );
        }

        return $cartReorderResponseTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function sanitizeSalesOrderItemIds(ArrayObject $itemTransfers): ArrayObject
    {
        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->setIdSalesOrderItem(null);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function executeCartReorderPreAddToCartPlugins(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $quoteProcessFlowName = $cartChangeTransfer->getQuoteOrFail()->getQuoteProcessFlow()?->getNameOrFail();
        $cartReorderPreAddToCartPlugins = $this->cartReorderPreAddToCartPluginStrategyResolver->get($quoteProcessFlowName);

        foreach ($cartReorderPreAddToCartPlugins as $cartReorderPreAddToCartPlugin) {
            $cartChangeTransfer = $cartReorderPreAddToCartPlugin->preAddToCart($cartChangeTransfer);
        }

        return $cartChangeTransfer;
    }
}
