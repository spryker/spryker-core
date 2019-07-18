<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Model;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface;

class QuoteValidator implements QuoteValidatorInterface
{
    /**
     * @var \Spryker\Zed\Cart\Business\Model\OperationInterface
     */
    protected $operation;

    /**
     * @var \Spryker\Zed\Cart\Business\Model\QuoteChangeObserverInterface
     */
    protected $changeNote;

    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\Cart\Business\Model\OperationInterface $operation
     * @param \Spryker\Zed\Cart\Business\Model\QuoteChangeObserverInterface $changeNote
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface $messengerFacade
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        OperationInterface $operation,
        QuoteChangeObserverInterface $changeNote,
        CartToMessengerInterface $messengerFacade,
        CartToQuoteFacadeInterface $quoteFacade
    ) {
        $this->operation = $operation;
        $this->changeNote = $changeNote;
        $this->messengerFacade = $messengerFacade;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer)
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful(false);

        if ($this->quoteFacade->isQuoteLocked($quoteTransfer)) {
            $quoteResponseTransfer->setIsSuccessful(true);

            return $quoteResponseTransfer;
        }

        if (!count($quoteTransfer->getItems())) {
            return $quoteResponseTransfer;
        }

        $originalQuoteTransfer = clone $quoteTransfer;
        $quoteTransfer = $this->operation->reloadItems($quoteTransfer);
        $this->changeNote->checkChanges($quoteTransfer, $originalQuoteTransfer);

        $quoteResponseTransfer = $this->addErrorsToQuoteResponse($quoteResponseTransfer);
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function addErrorsToQuoteResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\FlashMessagesTransfer|null $storedMessages */
        $storedMessages = $this->messengerFacade->getStoredMessages();

        if (!$storedMessages || count($storedMessages->getErrorMessages()) === 0) {
            return $quoteResponseTransfer->setIsSuccessful(true);
        }

        foreach ($storedMessages->getErrorMessages() as $errorMessage) {
            $quoteResponseTransfer->addError((new QuoteErrorTransfer())->setMessage($errorMessage));
        }

        return $quoteResponseTransfer;
    }
}
