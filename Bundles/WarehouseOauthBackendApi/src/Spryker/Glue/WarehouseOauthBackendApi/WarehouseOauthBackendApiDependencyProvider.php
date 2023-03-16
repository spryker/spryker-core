<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToAuthenticationFacadeBridge;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToWarehouseUserFacadeBridge;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Service\WarehouseOauthBackendApiToOauthServiceBridge;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Service\WarehouseOauthBackendApiToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Glue\WarehouseOauthBackendApi\WarehouseOauthBackendApiConfig getConfig()
 */
class WarehouseOauthBackendApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_OAUTH = 'SERVICE_OAUTH';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const FACADE_AUTHENTICATION = 'FACADE_AUTHENTICATION';

    /**
     * @var string
     */
    public const FACADE_WAREHOUSE_USER = 'FACADE_WAREHOUSE_USER';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addAuthenticationFacade($container);
        $container = $this->addWarehouseUserFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addOauthService($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addAuthenticationFacade(Container $container): Container
    {
        $container->set(static::FACADE_AUTHENTICATION, function (Container $container) {
            return new WarehouseOauthBackendApiToAuthenticationFacadeBridge(
                $container->getLocator()->authentication()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addWarehouseUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_WAREHOUSE_USER, function (Container $container) {
            return new WarehouseOauthBackendApiToWarehouseUserFacadeBridge(
                $container->getLocator()->warehouseUser()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addOauthService(Container $container): Container
    {
        $container->set(static::SERVICE_OAUTH, function (Container $container) {
            return new WarehouseOauthBackendApiToOauthServiceBridge(
                $container->getLocator()->oauth()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new WarehouseOauthBackendApiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }
}
