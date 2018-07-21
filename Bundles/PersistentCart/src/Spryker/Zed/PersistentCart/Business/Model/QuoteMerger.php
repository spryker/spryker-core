<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteMergeRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface;

class QuoteMerger implements QuoteMergerInterface
{
    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToCartFacadeInterface $cartFacade
     */
    public function __construct(
        PersistentCartToCartFacadeInterface $cartFacade
    ) {
        $this->cartFacade = $cartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteMergeRequestTransfer $quoteMergeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function merge(QuoteMergeRequestTransfer $quoteMergeRequestTransfer): QuoteTransfer
    {
        $targetQuote = clone $quoteMergeRequestTransfer->getTargetQuote();
        $targetQuote = $this->mergeItems($targetQuote, $quoteMergeRequestTransfer->getSourceQuote());

        return $targetQuote;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $targetQuote
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mergeItems(QuoteTransfer $targetQuote, QuoteTransfer $sourceQuote): QuoteTransfer
    {
        $cartChangeTransfer = $this->createCartChangeTransfer($targetQuote, $sourceQuote);
        $this->cartFacade->add($cartChangeTransfer);

        return $cartChangeTransfer->getQuote();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $targetQuote
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuote
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer(QuoteTransfer $targetQuote, QuoteTransfer $sourceQuote): CartChangeTransfer
    {
        return (new CartChangeTransfer())
            ->setQuote($targetQuote)
            ->setItems($sourceQuote->getItems());
    }
}
