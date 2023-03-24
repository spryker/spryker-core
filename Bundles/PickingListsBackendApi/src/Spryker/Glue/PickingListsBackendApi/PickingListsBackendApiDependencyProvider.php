<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\PickingListsBackendApi\Dependency\Client\PickingListsBackendApiToGlossaryStorageClientBridge;
use Spryker\Glue\PickingListsBackendApi\Dependency\Client\PickingListsBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeBridge;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToPickingListFacadeInterface;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToStockFacadeBridge;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToStockFacadeInterface;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToWarehouseUserFacadeBridge;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToWarehouseUserFacadeInterface;
use Spryker\Glue\PickingListsBackendApi\Dependency\Service\PickingListsBackendApiToUtilEncodingServiceBridge;
use Spryker\Glue\PickingListsBackendApi\Dependency\Service\PickingListsBackendApiToUtilEncodingServiceInterface;

/**
 * @method \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig getConfig()
 */
class PickingListsBackendApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PICKING_LIST = 'FACADE_PICKING_LIST';

    /**
     * @var string
     */
    public const FACADE_WAREHOUSE_USER = 'FACADE_WAREHOUSE_USER';

    /**
     * @var string
     */
    public const FACADE_STOCK = 'FACADE_STOCK';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

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
        $container = $this->addPickingListFacade($container);
        $container = $this->addWarehouseUserFacade($container);
        $container = $this->addStockFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addGlossaryStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addPickingListFacade(Container $container): Container
    {
        $container->set(static::FACADE_PICKING_LIST, function (Container $container): PickingListsBackendApiToPickingListFacadeInterface {
            return new PickingListsBackendApiToPickingListFacadeBridge(
                $container->getLocator()->pickingList()->facade(),
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
        $container->set(static::FACADE_WAREHOUSE_USER, function (Container $container): PickingListsBackendApiToWarehouseUserFacadeInterface {
            return new PickingListsBackendApiToWarehouseUserFacadeBridge(
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
    protected function addStockFacade(Container $container): Container
    {
        $container->set(static::FACADE_STOCK, function (Container $container): PickingListsBackendApiToStockFacadeInterface {
            return new PickingListsBackendApiToStockFacadeBridge(
                $container->getLocator()->stock()->facade(),
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
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container): PickingListsBackendApiToUtilEncodingServiceInterface {
            return new PickingListsBackendApiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
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
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container): PickingListsBackendApiToGlossaryStorageClientInterface {
            return new PickingListsBackendApiToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client(),
            );
        });

        return $container;
    }
}
