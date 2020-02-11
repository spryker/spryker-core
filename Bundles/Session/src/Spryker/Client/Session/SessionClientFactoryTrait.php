<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Session;

use Spryker\Client\Session\ServiceProvider\SessionClientServiceProvider;

/**
 * @deprecated Will be removed without replacement.
 */
trait SessionClientFactoryTrait
{
    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    public function getSessionClient()
    {
        return $this->getProvidedDependency(SessionClientServiceProvider::CLIENT_SESSION);
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return mixed
     */
    abstract public function getProvidedDependency($key);
}
