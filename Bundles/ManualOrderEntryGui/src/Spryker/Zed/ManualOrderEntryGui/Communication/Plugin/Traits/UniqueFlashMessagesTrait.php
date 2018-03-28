<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Traits;

use Generated\Shared\Transfer\MessageTransfer;

trait UniqueFlashMessagesTrait
{
    /**
     * @return void
     */
    protected function uniqueFlashMessages()
    {
        $flashMessagesTransfer = $this->messengerFacade->getStoredMessages();

        $this->addUniqueFlashMessagesByType($flashMessagesTransfer->getSuccessMessages(), 'addSuccessMessage');
        $this->addUniqueFlashMessagesByType($flashMessagesTransfer->getErrorMessages(), 'addErrorMessage');
        $this->addUniqueFlashMessagesByType($flashMessagesTransfer->getInfoMessages(), 'addInfoMessage');
    }

    /**
     * @param array $messages
     * @param string $addMethod
     *
     * @return void
     */
    protected function addUniqueFlashMessagesByType($messages, $addMethod)
    {
        if (!method_exists($this->messengerFacade, $addMethod)) {
            return;
        }

        $resultMessages = [];
        foreach ($messages as $message) {
            if (is_array($message)) {
                $resultMessages = array_merge($resultMessages, $message);
            } else {
                $resultMessages[] = $message;
            }
        }
        $resultMessages = array_unique($resultMessages);

        foreach ($resultMessages as $resultMessage) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setValue($resultMessage);

            $this->messengerFacade->$addMethod($messageTransfer);
        }
    }
}
