<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCart\Business\Replacer;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ServicePointCart\Dependency\Facade\ServicePointCartToCartFacadeInterface;

class QuoteItemReplacer implements QuoteItemReplacerInterface
{
    /**
     * @var \Spryker\Zed\ServicePointCart\Dependency\Facade\ServicePointCartToCartFacadeInterface
     */
    protected ServicePointCartToCartFacadeInterface $cartFacade;

    /**
     * @var list<\Spryker\Zed\ServicePointCartExtension\Dependency\Plugin\ServicePointQuoteItemReplaceStrategyPluginInterface>
     */
    protected array $servicePointQuoteItemReplaceStrategyPlugins;

    /**
     * @param \Spryker\Zed\ServicePointCart\Dependency\Facade\ServicePointCartToCartFacadeInterface $cartFacade
     * @param list<\Spryker\Zed\ServicePointCartExtension\Dependency\Plugin\ServicePointQuoteItemReplaceStrategyPluginInterface> $servicePointQuoteItemReplaceStrategyPlugins
     */
    public function __construct(
        ServicePointCartToCartFacadeInterface $cartFacade,
        array $servicePointQuoteItemReplaceStrategyPlugins
    ) {
        $this->cartFacade = $cartFacade;
        $this->servicePointQuoteItemReplaceStrategyPlugins = $servicePointQuoteItemReplaceStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceQuoteItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->executeStrategyPlugins($quoteTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $quoteTransfer = $this->cartFacade->reloadItems($quoteResponseTransfer->getQuoteTransferOrFail());
            $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeStrategyPlugins(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = (new QuoteResponseTransfer())
        ->setQuoteTransfer($quoteTransfer)
        ->setIsSuccessful(true);

        foreach ($this->servicePointQuoteItemReplaceStrategyPlugins as $servicePointQuoteItemReplaceStrategyPlugin) {
            if (!$servicePointQuoteItemReplaceStrategyPlugin->isApplicable($quoteTransfer)) {
                continue;
            }

            $quoteResponseTransfer = $servicePointQuoteItemReplaceStrategyPlugin->execute($quoteTransfer);

            if (!$quoteResponseTransfer->getIsSuccessfulOrFail()) {
                return $quoteResponseTransfer;
            }
        }

        return $quoteResponseTransfer;
    }
}
