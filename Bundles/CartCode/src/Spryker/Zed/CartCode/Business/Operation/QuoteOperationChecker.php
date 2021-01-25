<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCode\Business\Operation;

use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartCode\Dependency\Facade\CartCodeToQuoteFacadeInterface;

class QuoteOperationChecker implements QuoteOperationCheckerInterface
{
    protected const MESSAGE_TYPE_ERROR = 'error';

    protected const GLOSSARY_KEY_LOCKED_CART_CHANGE_DENIED = 'cart.locked.change_denied';

    /**
     * @var \Spryker\Zed\CartCode\Dependency\Facade\CartCodeToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\CartCode\Dependency\Facade\CartCodeToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(CartCodeToQuoteFacadeInterface $quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer|null
     */
    public function checkLockedQuoteResponse(QuoteTransfer $quoteTransfer): ?CartCodeResponseTransfer
    {
        if (!$this->quoteFacade->isQuoteLocked($quoteTransfer)) {
            return null;
        }

        return (new CartCodeResponseTransfer())
            ->setIsSuccessful(false)
            ->setQuote($quoteTransfer)
            ->addMessage((new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_LOCKED_CART_CHANGE_DENIED)
                ->setType(static::MESSAGE_TYPE_ERROR));
    }
}
