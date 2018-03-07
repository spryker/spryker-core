<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface;

class QuoteValidator implements QuoteValidatorInterface
{
    /**
     * @var \Spryker\Zed\Cart\Business\Model\OperationInterface
     */
    protected $operation;

    /**
     * @var \Spryker\Zed\Cart\Business\Model\QuoteChangeNoteInterface
     */
    protected $changeNote;

    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * QuoteValidator constructor.
     *
     * @param \Spryker\Zed\Cart\Business\Model\OperationInterface $operation
     * @param \Spryker\Zed\Cart\Business\Model\QuoteChangeNoteInterface $changeNote
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToMessengerInterface $messengerFacade
     */
    public function __construct(
        OperationInterface $operation,
        QuoteChangeNoteInterface $changeNote,
        CartToMessengerInterface $messengerFacade
    ) {
        $this->operation = $operation;
        $this->changeNote = $changeNote;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer)
    {
        $quoteValidationResponseTransfer = new QuoteValidationResponseTransfer();
        $quoteValidationResponseTransfer->setQuoteTransfer($quoteTransfer);
        $quoteValidationResponseTransfer->setIsSuccessful(false);
        if (count($quoteTransfer->getItems())) {
            $originalQuoteTransfer = clone $quoteTransfer;
            $quoteTransfer = $this->operation->reloadItems($quoteTransfer);
            $this->changeNote->checkChanges($quoteTransfer, $originalQuoteTransfer);
            $quoteValidationResponseTransfer->setIsSuccessful($this->checkErrorMessages());
            $quoteValidationResponseTransfer->setQuoteTransfer($quoteTransfer);
        }
        return $quoteValidationResponseTransfer;
    }

    /**
     * @return bool
     */
    protected function checkErrorMessages()
    {
        $storedMessages = $this->messengerFacade->getStoredMessages();

        return !$storedMessages || count($storedMessages->getErrorMessages()) === 0;
    }
}
