<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehousesBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\WarehousesBackendApi\Dependency\Facade\WarehousesBackendApiToStockFacadeInterface;
use Spryker\Glue\WarehousesBackendApi\Processor\Mapper\WarehouseResourceMapper;
use Spryker\Glue\WarehousesBackendApi\Processor\Mapper\WarehouseResourceMapperInterface;
use Spryker\Glue\WarehousesBackendApi\Processor\Reader\WarehouseResourceReader;
use Spryker\Glue\WarehousesBackendApi\Processor\Reader\WarehouseResourceReaderInterface;

/**
 * @method \Spryker\Glue\WarehousesBackendApi\WarehousesBackendApiConfig getConfig()
 */
class WarehousesBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\WarehousesBackendApi\Processor\Reader\WarehouseResourceReaderInterface
     */
    public function createWarehouseResourceReader(): WarehouseResourceReaderInterface
    {
        return new WarehouseResourceReader(
            $this->getStockFacade(),
            $this->createWarehouseResourceMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\WarehousesBackendApi\Processor\Mapper\WarehouseResourceMapperInterface
     */
    public function createWarehouseResourceMapper(): WarehouseResourceMapperInterface
    {
        return new WarehouseResourceMapper();
    }

    /**
     * @return \Spryker\Glue\WarehousesBackendApi\Dependency\Facade\WarehousesBackendApiToStockFacadeInterface
     */
    public function getStockFacade(): WarehousesBackendApiToStockFacadeInterface
    {
        return $this->getProvidedDependency(WarehousesBackendApiDependencyProvider::FACADE_STOCK);
    }
}
