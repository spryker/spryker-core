<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AppCatalogGui;

use Spryker\Client\AppCatalogGui\Dependency\External\AppCatalogGuiToHttpClientAdapterInterface;
use Spryker\Client\AppCatalogGui\Dependency\Service\AppCatalogGuiToUtilEncodingServiceInterface;
use Spryker\Client\AppCatalogGui\RequestExecutor\OauthRequestExecutor;
use Spryker\Client\AppCatalogGui\RequestExecutor\OauthRequestExecutorInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\AppCatalogGui\AppCatalogGuiConfig getConfig()
 */
class AppCatalogGuiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\AppCatalogGui\RequestExecutor\OauthRequestExecutorInterface
     */
    public function createAccessTokenClient(): OauthRequestExecutorInterface
    {
        return new OauthRequestExecutor(
            $this->getHttpClient(),
            $this->getServiceUtilEncoding(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Client\AppCatalogGui\Dependency\External\AppCatalogGuiToHttpClientAdapterInterface
     */
    public function getHttpClient(): AppCatalogGuiToHttpClientAdapterInterface
    {
        return $this->getProvidedDependency(AppCatalogGuiDependencyProvider::CLIENT_HTTP);
    }

    /**
     * @return \Spryker\Client\AppCatalogGui\Dependency\Service\AppCatalogGuiToUtilEncodingServiceInterface
     */
    public function getServiceUtilEncoding(): AppCatalogGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(AppCatalogGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
