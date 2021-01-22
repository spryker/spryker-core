<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Kernel;

use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;

trait ClientResolverAwareTrait
{
    /**
     * @var \Spryker\Client\Kernel\AbstractClient|null
     */
    protected $client;

    /**
     * @param \Spryker\Client\Kernel\AbstractClient $client
     *
     * @return $this
     */
    public function setClient(AbstractClient $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    protected function getClient()
    {
        if ($this->client === null) {
            $this->client = $this->resolveClient();
        }

        return $this->client;
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    private function resolveClient()
    {
        return $this->getClientResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Client\Kernel\ClassResolver\Client\ClientResolver
     */
    private function getClientResolver()
    {
        return new ClientResolver();
    }
}
