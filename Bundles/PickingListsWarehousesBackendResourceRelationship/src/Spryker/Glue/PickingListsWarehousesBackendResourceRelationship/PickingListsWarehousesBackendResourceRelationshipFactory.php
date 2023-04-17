<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsWarehousesBackendResourceRelationship;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Facade\PickingListsWarehousesBackendResourceRelationshipToPickingListFacadeInterface;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Resource\PickingListsWarehousesBackendResourceRelationshipToWarehousesBackendApiResourceInterface;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Processor\Expander\PickingListWarehouseResourceRelationshipExpander;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Processor\Expander\PickingListWarehouseResourceRelationshipExpanderInterface;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Processor\Filter\PickingListResourceFilter;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Processor\Filter\PickingListResourceFilterInterface;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Processor\Reader\PickingListWarehouseResourceRelationshipReader;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Processor\Reader\PickingListWarehouseResourceRelationshipReaderInterface;

class PickingListsWarehousesBackendResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Processor\Expander\PickingListWarehouseResourceRelationshipExpanderInterface
     */
    public function createPickingListWarehouseResourceRelationshipExpander(): PickingListWarehouseResourceRelationshipExpanderInterface
    {
        return new PickingListWarehouseResourceRelationshipExpander(
            $this->createPickingListWarehouseResourceRelationshipReader(),
            $this->createPickingListResourceFilter(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Processor\Reader\PickingListWarehouseResourceRelationshipReaderInterface
     */
    public function createPickingListWarehouseResourceRelationshipReader(): PickingListWarehouseResourceRelationshipReaderInterface
    {
        return new PickingListWarehouseResourceRelationshipReader(
            $this->getWarehousesBackendApiResource(),
            $this->getPickingListFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Processor\Filter\PickingListResourceFilterInterface
     */
    public function createPickingListResourceFilter(): PickingListResourceFilterInterface
    {
        return new PickingListResourceFilter();
    }

    /**
     * @return \Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Resource\PickingListsWarehousesBackendResourceRelationshipToWarehousesBackendApiResourceInterface
     */
    public function getWarehousesBackendApiResource(): PickingListsWarehousesBackendResourceRelationshipToWarehousesBackendApiResourceInterface
    {
        return $this->getProvidedDependency(PickingListsWarehousesBackendResourceRelationshipDependencyProvider::RESOURCE_WAREHOUSES_BACKEND_API);
    }

    /**
     * @return \Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Facade\PickingListsWarehousesBackendResourceRelationshipToPickingListFacadeInterface
     */
    public function getPickingListFacade(): PickingListsWarehousesBackendResourceRelationshipToPickingListFacadeInterface
    {
        return $this->getProvidedDependency(PickingListsWarehousesBackendResourceRelationshipDependencyProvider::FACADE_PICKING_LIST);
    }
}
