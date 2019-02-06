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
    public function getResponsesInfoMessages(): array
    {
        return $this->zedClient->getInfoStatusMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getResponsesErrorMessages(): array
    {
        return $this->zedClient->getErrorStatusMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getResponsesSuccessMessages(): array
    {
        return $this->zedClient->getSuccessStatusMessages();
    }

   /**
    * @return void
    */
    protected function pushResponseErrorMessagesToMessenger(): void
    {
        foreach ($this->getResponsesErrorMessages() as $errorMessage) {
            $this->messengerClient->addErrorMessage($errorMessage->getValue());
        }
    }

    /**
     * @return void
     */
    protected function pushResponseSuccessMessagesToMessenger(): void
    {
        foreach ($this->getResponsesSuccessMessages() as $successMessage) {
            $this->messengerClient->addSuccessMessage($successMessage->getValue());
        }
    }

    /**
     * @return void
     */
    protected function pushResponseInfoMessagesToMessenger(): void
    {
        foreach ($this->getResponsesInfoMessages() as $infoMessage) {
            $this->messengerClient->addInfoMessage($infoMessage->getValue());
        }
    }

    /**
     * @return void
     */
    public function addResponseMessagesToMessenger(): void
    {
        $this->pushResponseErrorMessagesToMessenger();
        $this->pushResponseSuccessMessagesToMessenger();
        $this->pushResponseInfoMessagesToMessenger();
    }
}
