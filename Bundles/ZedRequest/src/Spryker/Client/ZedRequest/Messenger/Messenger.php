<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Messenger;

use Spryker\Client\ZedRequest\Dependency\Client\ZedRequestToMessengerClientInterface;
use Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface;

class Messenger implements MessengerInterface
{
    /**
     * @var \Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface
     */
    protected $zedClient;

    /**
     * @var \Spryker\Client\ZedRequest\Dependency\Client\ZedRequestToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @param \Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface $zedClient
     * @param \Spryker\Client\ZedRequest\Dependency\Client\ZedRequestToMessengerClientInterface $messengerClient
     */
    public function __construct(
        AbstractZedClientInterface $zedClient,
        ZedRequestToMessengerClientInterface $messengerClient
    ) {
        $this->zedClient = $zedClient;
        $this->messengerClient = $messengerClient;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseInfoMessages()
    {
        if (!$this->zedClient->hasLastResponse()) {
            return [];
        }

        return $this->zedClient->getLastResponse()->getInfoMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseErrorMessages()
    {
        if (!$this->zedClient->hasLastResponse()) {
            return [];
        }

        return $this->zedClient->getLastResponse()->getErrorMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseSuccessMessages()
    {
        if (!$this->zedClient->hasLastResponse()) {
            return [];
        }

        return $this->zedClient->getLastResponse()->getSuccessMessages();
    }

    /**
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest()
    {
        $this->processErrorMessages();
        $this->processSuccessMessages();
        $this->processInfoMessages();
    }

    /**
     * @return void
     */
    protected function processErrorMessages()
    {
        foreach ($this->getLastResponseErrorMessages() as $errorMessage) {
            $this->messengerClient->addErrorMessage($errorMessage->getValue());
        }
    }

    /**
     * @return void
     */
    protected function processSuccessMessages()
    {
        foreach ($this->getLastResponseSuccessMessages() as $successMessage) {
            $this->messengerClient->addSuccessMessage($successMessage->getValue());
        }
    }

    /**
     * @return void
     */
    protected function processInfoMessages()
    {
        foreach ($this->getLastResponseInfoMessages() as $infoMessage) {
            $this->messengerClient->addInfoMessage($infoMessage->getValue());
        }
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getAllResponsesInfoMessages(): array
    {
        return $this->zedClient->getInfoStatusMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getAllResponsesErrorMessages(): array
    {
        return $this->zedClient->getErrorStatusMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getAllResponsesSuccessMessages(): array
    {
        return $this->zedClient->getSuccessStatusMessages();
    }

   /**
    * @return void
    */
    protected function addAllResponseErrorMessagesToMessenger(): void
    {
        foreach ($this->getAllResponsesErrorMessages() as $errorMessage) {
            $this->messengerClient->addErrorMessage($errorMessage);
        }
    }

    /**
     * @return void
     */
    protected function addAllResponseSuccessMessagesToMessenger(): void
    {
        foreach ($this->getAllResponsesSuccessMessages() as $successMessage) {
            $this->messengerClient->addSuccessMessage($successMessage);
        }
    }

    /**
     * @return void
     */
    protected function addAllResponseInfoMessagesToMessenger(): void
    {
        foreach ($this->getAllResponsesInfoMessages() as $infoMessage) {
            $this->messengerClient->addInfoMessage($infoMessage);
        }
    }

    /**
     * @return void
     */
    public function addAllResponseMessagesToMessenger(): void
    {
        $this->addAllResponseErrorMessagesToMessenger();
        $this->addAllResponseSuccessMessagesToMessenger();
        $this->addAllResponseInfoMessagesToMessenger();
    }
}
