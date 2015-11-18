<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\FlashMessenger\Business\Model;

use Generated\Shared\Transfer\FlashMessagesTransfer;
use SprykerFeature\Shared\ZedRequest\Client\Message;

class InMemoryMessageTray implements MessageTrayInterface
{
    /**
     * @var FlashMessagesTransfer
     */
    protected static $messages;

    /**
     * InMemoryMessageTray constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * @reurn void
     */
    public function init()
    {
        if (self::$messages === null) {
            self::$messages = new FlashMessagesTransfer();
        }
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage($message)
    {
        self::$messages->addSuccessMessage($this->createMessage($message));
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage($message)
    {
        self::$messages->addInfoMessage($this->createMessage($message));
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message)
    {
        self::$messages->addErrorMessage($this->createMessage($message));
    }

    protected function createMessage($message)
    {
        $messageTransfer = new Message();
        $messageTransfer->setMessage($message);

        return $messageTransfer;
    }

    /**
     * @return FlashMessagesTransfer
     */
    public static function getMessages()
    {
        return self::$messages;
    }

}
