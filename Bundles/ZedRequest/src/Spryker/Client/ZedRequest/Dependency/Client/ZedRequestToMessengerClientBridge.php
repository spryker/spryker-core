<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Dependency\Client;

class ZedRequestToMessengerClientBridge implements ZedRequestToMessengerClientInterface
{
    /**
     * @var \Spryker\Client\Messenger\MessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @param \Spryker\Client\Messenger\MessengerClientInterface $messengerClient
     */
    public function __construct($messengerClient)
    {
        $this->messengerClient = $messengerClient;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage(string $message): void
    {
        $this->messengerClient->addErrorMessage($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addInfoMessage(string $message): void
    {
        $this->messengerClient->addInfoMessage($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage(string $message): void
    {
        $this->messengerClient->addSuccessMessage($message);
    }
}
