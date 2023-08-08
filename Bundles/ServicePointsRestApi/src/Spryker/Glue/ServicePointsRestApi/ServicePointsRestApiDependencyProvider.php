<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToGlossaryStorageClientBridge;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointSearchClientBridge;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientBridge;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToStoreClientBridge;

/**
 * @method \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig getConfig()
 */
class ServicePointsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SERVICE_POINT_STORAGE = 'CLIENT_SERVICE_POINT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_SERVICE_POINT_SEARCH = 'CLIENT_SERVICE_POINT_SEARCH';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addServicePointStorageClient($container);
        $container = $this->addServicePointSearchClient($container);
        $container = $this->addStoreClient($container);
        $container = $this->addGlossaryStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addServicePointStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_SERVICE_POINT_STORAGE, function (Container $container) {
            return new ServicePointsRestApiToServicePointStorageClientBridge(
                $container->getLocator()->servicePointStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addServicePointSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SERVICE_POINT_SEARCH, function (Container $container) {
            return new ServicePointsRestApiToServicePointSearchClientBridge(
                $container->getLocator()->servicePointSearch()->client(),
            );
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
            return new ServicePointsRestApiToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
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
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return new ServicePointsRestApiToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client(),
            );
        });

        return $container;
    }
}
