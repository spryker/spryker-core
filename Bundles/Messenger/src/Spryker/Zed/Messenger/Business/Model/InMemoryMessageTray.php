<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger\Business\Model;

use Generated\Shared\Transfer\FlashMessagesTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class InMemoryMessageTray extends BaseMessageTray implements MessageTrayInterface
{
    /**
     * @var \Generated\Shared\Transfer\FlashMessagesTransfer|null
     */
    protected static $messages;

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addSuccessMessage(MessageTransfer $message)
    {
        self::getFlashMessagesTransfer()->addSuccessMessage(
            $this->translate(
                $message->getValue(),
                $message->getParameters()
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addInfoMessage(MessageTransfer $message)
    {
        self::getFlashMessagesTransfer()->addInfoMessage(
            $this->translate(
                $message->getValue(),
                $message->getParameters()
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $message)
    {
        self::getFlashMessagesTransfer()->addErrorMessage(
            $this->translate(
                $message->getValue(),
                $message->getParameters()
            )
        );
    }

    /**
     * @return \Generated\Shared\Transfer\FlashMessagesTransfer
     */
    public function getMessages()
    {
        return self::$messages ?: static::getFlashMessagesTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\FlashMessagesTransfer
     */
    protected static function getFlashMessagesTransfer()
    {
        if (self::$messages === null) {
            self::$messages = new FlashMessagesTransfer();
        }

        return self::$messages;
    }
}
