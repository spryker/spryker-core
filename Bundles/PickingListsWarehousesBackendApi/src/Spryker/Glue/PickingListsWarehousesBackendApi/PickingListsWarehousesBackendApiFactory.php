<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsWarehousesBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\PickingListsWarehousesBackendApi\Dependency\Facade\PickingListsWarehousesBackendApiToPickingListFacadeInterface;
use Spryker\Glue\PickingListsWarehousesBackendApi\Dependency\Resource\PickingListsWarehousesBackendApiToWarehousesBackendApiResourceInterface;
use Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Expander\PickingListWarehouseResourceRelationshipExpander;
use Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Expander\PickingListWarehouseResourceRelationshipExpanderInterface;
use Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Filter\PickingListResourceFilter;
use Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Filter\PickingListResourceFilterInterface;
use Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Reader\PickingListWarehouseResourceRelationshipReader;
use Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Reader\PickingListWarehouseResourceRelationshipReaderInterface;

class PickingListsWarehousesBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Expander\PickingListWarehouseResourceRelationshipExpanderInterface
     */
    public function createPickingListWarehouseResourceRelationshipExpander(): PickingListWarehouseResourceRelationshipExpanderInterface
    {
        return new PickingListWarehouseResourceRelationshipExpander(
            $this->createPickingListWarehouseResourceRelationshipReader(),
            $this->createPickingListResourceFilter(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Reader\PickingListWarehouseResourceRelationshipReaderInterface
     */
    public function createPickingListWarehouseResourceRelationshipReader(): PickingListWarehouseResourceRelationshipReaderInterface
    {
        return new PickingListWarehouseResourceRelationshipReader(
            $this->getWarehousesBackendApiResource(),
            $this->getPickingListFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Filter\PickingListResourceFilterInterface
     */
    public function createPickingListResourceFilter(): PickingListResourceFilterInterface
    {
        return new PickingListResourceFilter();
    }

    /**
     * @return \Spryker\Glue\PickingListsWarehousesBackendApi\Dependency\Resource\PickingListsWarehousesBackendApiToWarehousesBackendApiResourceInterface
     */
    public function getWarehousesBackendApiResource(): PickingListsWarehousesBackendApiToWarehousesBackendApiResourceInterface
    {
        return $this->getProvidedDependency(PickingListsWarehousesBackendApiDependencyProvider::RESOURCE_WAREHOUSES_BACKEND_API);
    }

    /**
     * @return \Spryker\Glue\PickingListsWarehousesBackendApi\Dependency\Facade\PickingListsWarehousesBackendApiToPickingListFacadeInterface
     */
    public function getPickingListFacade(): PickingListsWarehousesBackendApiToPickingListFacadeInterface
    {
        return $this->getProvidedDependency(PickingListsWarehousesBackendApiDependencyProvider::FACADE_PICKING_LIST);
    }
}
