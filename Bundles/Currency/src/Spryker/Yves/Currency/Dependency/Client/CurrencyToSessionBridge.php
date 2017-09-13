<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency\Dependency\Client;

use Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface;

class CurrencyToSessionBridge implements CurrencyToSessionInterface
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
     * @return void
     */
    public function set($name, $value)
    {
        $this->sessionClient->set($name, $value);
    }

    /**
     * @param string $name The attribute name
     * @param mixed|null $default The default value if not found
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return $this->sessionClient->get($name, $default);
    }

}
