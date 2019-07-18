<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger\Business\Model;

use Generated\Shared\Transfer\FlashMessagesTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionMessageTray implements MessageTrayInterface
{
    /**
     * @var \Spryker\Zed\Messenger\Business\Model\MessageTranslatorInterface
     */
    protected $messageTranslator;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessageTranslatorInterface $messageTranslator
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(
        MessageTranslatorInterface $messageTranslator,
        SessionInterface $session
    ) {
        $this->messageTranslator = $messageTranslator;
        $this->session = $session;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addSuccessMessage(MessageTransfer $message)
    {
        $this->addToSession(
            MessageTrayInterface::FLASH_MESSAGES_SUCCESS,
            $this->messageTranslator->translate(
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
        $this->addToSession(
            MessageTrayInterface::FLASH_MESSAGES_INFO,
            $this->messageTranslator->translate(
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
        $this->addToSession(
            MessageTrayInterface::FLASH_MESSAGES_ERROR,
            $this->messageTranslator->translate(
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
     * @return \Generated\Shared\Transfer\FlashMessagesTransfer
     */
    protected function createFlashMessageTransfer()
    {
        return new FlashMessagesTransfer();
    }
}
