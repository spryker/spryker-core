<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToCountryClientBridge;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToCurrencyClientBridge;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToGlossaryStorageClientBridge;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToGlossaryStorageClientInterface;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToLocaleClientBridge;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreClientBridge;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientBridge;

/**
 * @method \Spryker\Glue\StoresApi\StoresApiConfig getConfig()
 */
class StoresApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORE_STORAGE = 'CLIENT_STORE_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_COUNTRY = 'CLIENT_COUNTRY';

    /**
     * @var string
     */
    public const CLIENT_CURRENCY = 'CLIENT_CURRENCY';

    /**
     * @var string
     */
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addStoreStorageClient($container);
        $container = $this->addCountryClient($container);
        $container = $this->addCurrencyClient($container);
        $container = $this->addGlossaryStorageClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStoreStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE_STORAGE, function (Container $container) {
            return new StoresApiToStoreStorageClientBridge(
                $container->getLocator()->storeStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCountryClient(Container $container): Container
    {
        $container->set(static::CLIENT_COUNTRY, function (Container $container) {
            return new StoresApiToCountryClientBridge($container->getLocator()->country()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCurrencyClient(Container $container): Container
    {
        $container->set(static::CLIENT_CURRENCY, function (Container $container) {
            return new StoresApiToCurrencyClientBridge($container->getLocator()->currency()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container): StoresApiToGlossaryStorageClientInterface {
            return new StoresApiToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new StoresApiToLocaleClientBridge($container->getLocator()->locale()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new StoresApiToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }
}
