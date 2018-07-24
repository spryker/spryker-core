<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Glue\CustomersRestApi\Dependency\Client;

class CustomerRestApiToSessionClientBridge implements CustomerRestApiToSessionClientInterface
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
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set($key, $value): void
    {
        $this->sessionClient->set($key, $value);
    }
}
