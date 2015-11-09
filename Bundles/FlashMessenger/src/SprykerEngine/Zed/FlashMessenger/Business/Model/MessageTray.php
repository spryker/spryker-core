<?php

namespace SprykerEngine\Zed\FlashMessenger\Business\Model;

use Generated\Shared\Transfer\FlashMessagesTransfer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MessageTray
{

    const FLASH_MESSAGES_SUCCESS = 'flash.messages.success';

    const FLASH_MESSAGES_ERROR= 'flash.messages.error';

    const FLASH_MESSAGES_INFO = 'flash.messages.info';

    /**
     * @var FlashMessagesTransfer
     */
    protected static $messages;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->init();
    }

    public function init()
    {
        if (is_null(self::$messages)) {
            self::$messages = new FlashMessagesTransfer();
        }
    }

    /**
     * @param $message
     */
    public function addSuccessMessage($message)
    {
        $this->addToSession(self::FLASH_MESSAGES_SUCCESS, $message);
        self::$messages->addSuccessMessage($message);
    }

    /**
     * @param $message
     */
    public function addInfoMessage($message)
    {
        $this->addToSession(self::FLASH_MESSAGES_INFO, $message);
        self::$messages->addInfoMessage($message);
    }

    /**
     * @param $message
     */
    public function addErrorMessage($message)
    {
        $this->addToSession(self::FLASH_MESSAGES_ERROR, $message);
        self::$messages->addErrorMessage($message);
    }

    /**
     * TODO Translation for Zed needed here
     *
     * @param $key
     * @param $value
     */
    protected function addToSession($key, $value)
    {
        $this->session->getFlashBag()->add($key, $value);
    }

}
