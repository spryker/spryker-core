<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\FlashMessenger\Business\Model;

use Generated\Shared\Transfer\FlashMessagesTransfer;

class InMemoryMessageTray implements MessageTrayInterface
{
    /**
     * @var FlashMessagesTransfer
     */
    protected static $messages;

    /**
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage($message)
    {
        self::getFlashMessagesTransfer()->addSuccessMessage($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage($message)
    {
        self::getFlashMessagesTransfer()->addInfoMessage($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message)
    {
        self::getFlashMessagesTransfer()->addErrorMessage($message);
    }

    /**
     * @return FlashMessagesTransfer
     */
    public function getMessages()
    {
        return self::$messages;
    }

    /**
     * @return FlashMessagesTransfer
     */
    protected static function getFlashMessagesTransfer()
    {
        if (self::$messages === null) {
            self::$messages = new FlashMessagesTransfer();
        }

        return self::$messages;
    }

}
