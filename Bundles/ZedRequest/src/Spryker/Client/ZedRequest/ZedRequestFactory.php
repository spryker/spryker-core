<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\Client\HttpClient;
use Spryker\Client\ZedRequest\Client\ZedClient;
use Spryker\Shared\Config\Config;

class ZedRequestFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\ZedRequest\Client\ZedClient
     */
    public function createClient()
    {
        return new ZedClient(
            $this->createHttpClient()
        );
    }

    /**
     * @return \Spryker\Client\ZedRequest\Client\HttpClient
     */
    protected function createHttpClient()
    {
        $httpClient = new HttpClient(
            $this->getProvidedDependency(ZedRequestDependencyProvider::CLIENT_AUTH),
            $this->getConfig()->getZedRequestBaseUrl(),
            $this->getConfig()->getRawToken(),
            $this->getConfig()->isAuthenticationEnabled()
        );

        return $httpClient;
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestConfig
     */
    protected function getConfig()
    {
        return new ZedRequestConfig(Config::getInstance());
    }

}
