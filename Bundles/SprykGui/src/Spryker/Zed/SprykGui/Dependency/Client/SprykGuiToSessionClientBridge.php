<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Dependency\Client;

class SprykGuiToSessionClientBridge implements SprykGuiToSessionClientInterface
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
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->sessionClient->get($name, $default);
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
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->sessionClient->has($name);
    }
}
