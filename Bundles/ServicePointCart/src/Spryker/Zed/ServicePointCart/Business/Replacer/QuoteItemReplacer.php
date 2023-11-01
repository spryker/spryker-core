<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCart\Business\Replacer;

use Generated\Shared\Transfer\QuoteReplacementResponseTransfer;
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
     * @return \Generated\Shared\Transfer\QuoteReplacementResponseTransfer
     */
    public function replaceQuoteItems(QuoteTransfer $quoteTransfer): QuoteReplacementResponseTransfer
    {
        $quoteReplacementResponseTransfer = $this->executeReplaceStrategyPlugin($quoteTransfer);

        $replacedQuoteTransfer = (new QuoteTransfer())->fromArray($quoteReplacementResponseTransfer->getQuoteOrFail()->toArray(), true);
        $quoteResponseTransfer = $this->cartFacade->reloadItemsInQuote($quoteReplacementResponseTransfer->getQuoteOrFail());
        if (!$quoteResponseTransfer->getIsSuccessfulOrFail()) {
            return $quoteReplacementResponseTransfer->setQuote($replacedQuoteTransfer);
        }

        return $quoteReplacementResponseTransfer->setQuote($quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteReplacementResponseTransfer
     */
    protected function executeReplaceStrategyPlugin(QuoteTransfer $quoteTransfer): QuoteReplacementResponseTransfer
    {
        $quoteReplacementResponseTransfer = (new QuoteReplacementResponseTransfer())
            ->setQuote($quoteTransfer);

        foreach ($this->servicePointQuoteItemReplaceStrategyPlugins as $servicePointQuoteItemReplaceStrategyPlugin) {
            if (!$servicePointQuoteItemReplaceStrategyPlugin->isApplicable($quoteTransfer)) {
                continue;
            }

            return $servicePointQuoteItemReplaceStrategyPlugin->execute($quoteTransfer);
        }

        return $quoteReplacementResponseTransfer;
    }
}
