<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Messenger\Dependency\Client;

class MessengerToSessionClientBridge implements MessengerToSessionClientInterface
{
    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $sessionClient;

    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     */
    public function __construct($sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param string $name
     *
     * @return \Symfony\Component\HttpFoundation\Session\SessionBagInterface
     */
    public function getBag($name)
    {
        return $this->sessionClient->getBag($name);
    }
}
