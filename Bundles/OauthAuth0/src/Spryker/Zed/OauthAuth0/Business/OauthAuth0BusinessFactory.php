<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAuth0\Business;

use Spryker\Client\OauthAuth0\OauthAuth0ClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OauthAuth0\Business\Expander\CacheKeySeedAccessTokenRequestExpander;
use Spryker\Zed\OauthAuth0\Business\Expander\CacheKeySeedAccessTokenRequestExpanderInterface;
use Spryker\Zed\OauthAuth0\Business\Provider\OauthAuth0TokenProvider;
use Spryker\Zed\OauthAuth0\Business\Provider\OauthAuth0TokenProviderInterface;
use Spryker\Zed\OauthAuth0\OauthAuth0DependencyProvider;

/**
 * @method \Spryker\Zed\OauthAuth0\OauthAuth0Config getConfig()
 */
class OauthAuth0BusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OauthAuth0\Business\Provider\OauthAuth0TokenProviderInterface
     */
    public function createOauthAuth0TokenProvider(): OauthAuth0TokenProviderInterface
    {
        return new OauthAuth0TokenProvider($this->getOauthAuth0Client());
    }

    /**
     * @return \Spryker\Client\OauthAuth0\OauthAuth0ClientInterface
     */
    public function getOauthAuth0Client(): OauthAuth0ClientInterface
    {
        return $this->getProvidedDependency(OauthAuth0DependencyProvider::CLIENT_OAUTH_AUTH0);
    }

    /**
     * @return \Spryker\Zed\OauthAuth0\Business\Expander\CacheKeySeedAccessTokenRequestExpanderInterface
     */
    public function createCacheKeySeedAccessTokenRequestExpander(): CacheKeySeedAccessTokenRequestExpanderInterface
    {
        return new CacheKeySeedAccessTokenRequestExpander(
            $this->getConfig(),
        );
    }
}
