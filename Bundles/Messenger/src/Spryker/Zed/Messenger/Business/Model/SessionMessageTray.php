<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Messenger\Business\Model;

use Generated\Shared\Transfer\FlashMessagesTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Spryker\Zed\Messenger\Dependency\Facade\MessengerToGlossaryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionMessageTray extends BaseMessageTray implements MessageTrayInterface
{

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @param SessionInterface $session
     * @param MessengerToGlossaryInterface $glossaryFacade
     */
    public function __construct(SessionInterface $session, MessengerToGlossaryInterface $glossaryFacade)
    {
        parent::__construct($glossaryFacade);
        $this->session = $session;
    }

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addSuccessMessage(MessageTransfer $message)
    {
        $this->addToSession(
            MessageTrayInterface::FLASH_MESSAGES_SUCCESS,
            $this->translate(
                $message->getValue(),
                $message->getParameters()
            )
        );
    }

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addInfoMessage(MessageTransfer $message)
    {
        $this->addToSession(
            MessageTrayInterface::FLASH_MESSAGES_INFO,
            $this->translate(
                $message->getValue(),
                $message->getParameters()
            )
        );
    }

    /**
     * @param MessageTransfer $message
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $message)
    {
        $this->addToSession(
            MessageTrayInterface::FLASH_MESSAGES_ERROR,
            $this->translate(
                $message->getValue(),
                $message->getParameters()
            )
        );
    }

    /**
     * @return FlashMessagesTransfer
     */
    public function getMessages()
    {
        $flashMessagesTransfer = $this->createFlashMessageTransfer();

        $sessionFlashBag = $this->session->getFlashBag();

        $flashMessagesTransfer->setErrorMessages([$sessionFlashBag->get(MessageTrayInterface::FLASH_MESSAGES_ERROR)]);
        $flashMessagesTransfer->setInfoMessages([$sessionFlashBag->get(MessageTrayInterface::FLASH_MESSAGES_INFO)]);
        $flashMessagesTransfer->setSuccessMessages([$sessionFlashBag->get(MessageTrayInterface::FLASH_MESSAGES_SUCCESS)]);

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
