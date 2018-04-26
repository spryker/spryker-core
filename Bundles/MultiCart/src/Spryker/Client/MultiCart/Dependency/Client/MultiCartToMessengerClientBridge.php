<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Dependency\Client;

class MultiCartToMessengerClientBridge implements MultiCartToMessengerClientInterface
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
    public function addInfoMessage($message)
    {
        return $this->messengerClient->addInfoMessage($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addErrorMessage($message)
    {
        return $this->messengerClient->addErrorMessage($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addSuccessMessage($message)
    {
        return $this->messengerClient->addSuccessMessage($message);
    }
}
