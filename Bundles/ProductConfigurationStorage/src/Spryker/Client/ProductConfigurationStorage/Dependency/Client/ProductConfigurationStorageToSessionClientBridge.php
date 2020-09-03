<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Dependency\Client;

class ProductConfigurationStorageToSessionClientBridge implements ProductConfigurationStorageToSessionClientInterface
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
     * @param $value
     *
     * @return mixed|void
     */
    public function set(string $name, $value)
    {
        $this->sessionClient->set($name, $value);
    }

    /**
     * @param string $name
     * @param null $default
     *
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->sessionClient->get($name, $default);
    }
}
