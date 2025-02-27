<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartReorder\Creator;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartReorder\Dependency\Client\CartReorderToQuoteClientInterface;
use Spryker\Client\CartReorder\Zed\CartReorderStubInterface;

class CartReorderCreator implements CartReorderCreatorInterface
{
    /**
     * @param \Spryker\Client\CartReorder\Zed\CartReorderStubInterface $cartReorderStub
     * @param \Spryker\Client\CartReorder\Dependency\Client\CartReorderToQuoteClientInterface $quoteClient
     * @param list<\Spryker\Client\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface> $cartReorderQuoteProviderStrategyPlugins
     */
    public function __construct(
        protected CartReorderStubInterface $cartReorderStub,
        protected CartReorderToQuoteClientInterface $quoteClient,
        protected array $cartReorderQuoteProviderStrategyPlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function reorder(CartReorderRequestTransfer $cartReorderRequestTransfer): CartReorderResponseTransfer
    {
        $quoteTransfer = $this->executeCartReorderQuoteProviderStrategyPlugins($cartReorderRequestTransfer);
        $cartReorderRequestTransfer->setQuote($quoteTransfer);

        $cartReorderResponseTransfer = $this->cartReorderStub->reorder($cartReorderRequestTransfer);

        if (!$cartReorderResponseTransfer->getErrors()->count()) {
            $this->quoteClient->setQuote($cartReorderResponseTransfer->getQuoteOrFail());
        }

        return $cartReorderResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function executeCartReorderQuoteProviderStrategyPlugins(
        CartReorderRequestTransfer $cartReorderRequestTransfer
    ): ?QuoteTransfer {
        if ($cartReorderRequestTransfer->getQuote()) {
            return $cartReorderRequestTransfer->getQuoteOrFail();
        }

        foreach ($this->cartReorderQuoteProviderStrategyPlugins as $cartReorderQuoteProviderStrategyPlugin) {
            if ($cartReorderQuoteProviderStrategyPlugin->isApplicable($cartReorderRequestTransfer)) {
                return $cartReorderQuoteProviderStrategyPlugin->execute($cartReorderRequestTransfer);
            }
        }

        return null;
    }
}
