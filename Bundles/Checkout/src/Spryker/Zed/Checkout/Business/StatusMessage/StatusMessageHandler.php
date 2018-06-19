<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\StatusMessage;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToMessengerFacadeInterface;

class StatusMessageHandler implements StatusMessageHandlerInterface
{
    /**
     * @var \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * MessageHandler constructor.
     *
     * @param \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(CheckoutToMessengerFacadeInterface $messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $messageTransfer): void
    {
        $storedMessages = $this->messengerFacade->getStoredMessages();

        if (!$storedMessages || !$storedMessages->getErrorMessages()) {
            $this->messengerFacade->addErrorMessage($messageTransfer);
            return;
        }

        if (!\in_array($messageTransfer->getValue(), $storedMessages->getErrorMessages(), true)) {
            $this->messengerFacade->addErrorMessage($messageTransfer);
        }
    }
}
