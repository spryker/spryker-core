<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Client\ServicePointsBackendApiToGlossaryStorageClientBridge;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Facade\ServicePointsBackendApiToServicePointFacadeBridge;

/**
 * @method \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig getConfig()
 */
class ServicePointsBackendApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_SERVICE_POINT = 'FACADE_SERVICE_POINT';

    /**
     * @var string
     */
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);

        $container = $this->addServicePointFacade($container);
        $container = $this->addGlossaryStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addServicePointFacade(Container $container): Container
    {
        $container->set(static::FACADE_SERVICE_POINT, function (Container $container) {
            return new ServicePointsBackendApiToServicePointFacadeBridge(
                $container->getLocator()->servicePoint()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return new ServicePointsBackendApiToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client(),
            );
        });

        return $container;
    }
}
