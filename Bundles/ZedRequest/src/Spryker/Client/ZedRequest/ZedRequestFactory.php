<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\Client\HttpClient;
use Spryker\Client\ZedRequest\Client\ZedClient;

/**
 * @method \Spryker\Client\ZedRequest\ZedRequestConfig getConfig()
 */
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
            $this->getConfig()->getZedRequestBaseUrl(),
            $this->getConfig()->getRawToken(),
            $this->getConfig()->isAuthenticationEnabled(),
            $this->getUtilTextService(),
            $this->getUtilNetworkService(),
            $this->createTokenOptions()
        );

        return $httpClient;
    }

    /**
     * @return array
     */
    protected function createTokenOptions()
    {
        return [
            'cost' => $this->getConfig()->getHashCost(),
        ];
    }

    /**
     * @return \Spryker\Service\UtilNetwork\UtilNetworkServiceInterface
     */
    protected function getUtilNetworkService()
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::SERVICE_NETWORK);
    }

    /**
     * @return \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected function getUtilTextService()
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::SERVICE_TEXT);
    }

}
