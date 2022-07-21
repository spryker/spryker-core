<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthAuth0;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\OauthAuth0\Auth0\Auth0;
use Spryker\Client\OauthAuth0\Dependency\External\Auth0AdapterInterface;

class OauthAuth0Factory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\OauthAuth0\Dependency\External\Auth0AdapterInterface
     */
    public function getAuth0Adapter(): Auth0AdapterInterface
    {
        return $this->getProvidedDependency(OauthAuth0DependencyProvider::CLIENT_AUTH0_ADAPTER);
    }

    /**
     * @return \Spryker\Client\OauthAuth0\Auth0\Auth0
     */
    public function createAuth0(): Auth0
    {
        return new Auth0($this->getAuth0Adapter());
    }
}
