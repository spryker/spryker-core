<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Client;

class AgentSecurityMerchantPortalGuiToSessionClientBridge implements AgentSecurityMerchantPortalGuiToSessionClientInterface
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
     * @param mixed $value
     *
     * @return mixed
     */
    public function set(string $name, $value)
    {
        return $this->sessionClient->set($name, $value);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function remove(string $name)
    {
        return $this->sessionClient->remove($name);
    }
}
