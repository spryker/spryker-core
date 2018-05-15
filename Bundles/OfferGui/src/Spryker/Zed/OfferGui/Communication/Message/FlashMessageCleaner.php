<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Message;

use Generated\Shared\Transfer\FlashMessagesTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMessengerFacadeInterface;

class FlashMessageCleaner implements FlashMessageCleanerInterface
{
    /**
     * @var \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(OfferGuiToMessengerFacadeInterface $messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @return void
     */
    public function clearDuplicateMessages(): void
    {
        $flashMessagesTransfer = $this->messengerFacade->getStoredMessages();

        $this->clearSuccessDuplicateMessages($flashMessagesTransfer);
        $this->clearInfoDuplicateMessages($flashMessagesTransfer);
        $this->clearErrorDuplicateMessages($flashMessagesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FlashMessagesTransfer $flashMessagesTransfer
     *
     * @return void
     */
    protected function clearSuccessDuplicateMessages(FlashMessagesTransfer $flashMessagesTransfer): void
    {
        $successMessages = $this->uniqueMessages($flashMessagesTransfer->getSuccessMessages());
        foreach ($successMessages as $successMessage) {
            $this->messengerFacade->addSuccessMessage($this->createMessageTransfer($successMessage));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FlashMessagesTransfer $flashMessagesTransfer
     *
     * @return void
     */
    protected function clearInfoDuplicateMessages(FlashMessagesTransfer $flashMessagesTransfer): void
    {
        $successMessages = $this->uniqueMessages($flashMessagesTransfer->getInfoMessages());
        foreach ($successMessages as $successMessage) {
            $this->messengerFacade->addInfoMessage($this->createMessageTransfer($successMessage));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FlashMessagesTransfer $flashMessagesTransfer
     *
     * @return void
     */
    protected function clearErrorDuplicateMessages(FlashMessagesTransfer $flashMessagesTransfer): void
    {
        $successMessages = $this->uniqueMessages($flashMessagesTransfer->getErrorMessages());
        foreach ($successMessages as $successMessage) {
            $this->messengerFacade->addErrorMessage($this->createMessageTransfer($successMessage));
        }
    }

    /**
     * @param string[] $messages
     *
     * @return string[]
     */
    protected function uniqueMessages(array $messages): array
    {
        $result = [];
        foreach ($messages as $message) {
            if (is_array($message)) {
                $result = array_merge($result, $message);
                continue;
            }
            $result[] = $message;
        }

        return array_unique($result);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $message): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($message);
    }
}
