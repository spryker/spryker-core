<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\FlashMessenger\Business\Model;

use Generated\Shared\Transfer\FlashMessagesTransfer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionMessageTray implements MessageTrayInterface
{
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
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage($message)
    {
        $this->addToSession(MessageTrayInterface::FLASH_MESSAGES_SUCCESS, $message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage($message)
    {
        $this->addToSession(MessageTrayInterface::FLASH_MESSAGES_INFO, $message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message)
    {
        $this->addToSession(MessageTrayInterface::FLASH_MESSAGES_ERROR, $message);
    }

    /**
     * @return FlashMessagesTransfer
     */
    public function getMessages()
    {
        $flashMessagesTransfer = $this->createFlashMessageTransfer();

        $sessionFlashBag = $this->session->getFlashBag();

        $flashMessagesTransfer->setErrorMessages([$sessionFlashBag->get(MessageTray::FLASH_MESSAGES_ERROR)]);
        $flashMessagesTransfer->setInfoMessages([$sessionFlashBag->get(MessageTray::FLASH_MESSAGES_INFO)]);
        $flashMessagesTransfer->setSuccessMessages([$sessionFlashBag->get(MessageTray::FLASH_MESSAGES_SUCCESS)]);

        return $flashMessagesTransfer;

    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    protected function addToSession($key, $value)
    {
        $this->session->getFlashBag()->add($key, $value);
    }

    /**
     * @return FlashMessagesTransfer
     */
    protected function createFlashMessageTransfer()
    {
        return new FlashMessagesTransfer();
    }
}
