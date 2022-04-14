<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppCatalogGui\Business;

use Spryker\Client\AppCatalogGui\AppCatalogGuiClientInterface;
use Spryker\Zed\AppCatalogGui\AppCatalogGuiDependencyProvider;
use Spryker\Zed\AppCatalogGui\Business\AccessToken\AccessTokenReader;
use Spryker\Zed\AppCatalogGui\Business\AccessToken\AccessTokenReaderInterface;
use Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToStoreReferenceFacadeInterface;
use Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToTranslatorFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AppCatalogGui\AppCatalogGuiConfig getConfig()
 */
class AppCatalogGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AppCatalogGui\Business\AccessToken\AccessTokenReaderInterface
     */
    public function createAccessTokenReader(): AccessTokenReaderInterface
    {
        return new AccessTokenReader(
            $this->getAppCatalogGuiClient(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToStoreReferenceFacadeInterface
     */
    public function getStoreReferenceFacade(): AppCatalogGuiToStoreReferenceFacadeInterface
    {
        return $this->getProvidedDependency(AppCatalogGuiDependencyProvider::FACADE_STORE_REFERENCE);
    }

    /**
     * @return \Spryker\Client\AppCatalogGui\AppCatalogGuiClientInterface
     */
    public function getAppCatalogGuiClient(): AppCatalogGuiClientInterface
    {
        return $this->getProvidedDependency(AppCatalogGuiDependencyProvider::CLIENT_APP_CATALOG_GUI);
    }

    /**
     * @return \Spryker\Zed\AppCatalogGui\Dependency\Facade\AppCatalogGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): AppCatalogGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(AppCatalogGuiDependencyProvider::FACADE_TRANSLATOR);
    }
}
