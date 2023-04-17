<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsShipmentsBackendResourceRelationship;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Dependency\Resource\PickingListsShipmentsBackendResourceRelationshipToShipmentsBackendApiResourceInterface;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Expander\PickingListsSalesShipmentsResourceRelationshipExpander;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Expander\PickingListsSalesShipmentsResourceRelationshipExpanderInterface;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilter;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Reader\SalesShipmentResourceRelationshipReader;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Reader\SalesShipmentResourceRelationshipReaderInterface;

class PickingListsShipmentsBackendResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Expander\PickingListsSalesShipmentsResourceRelationshipExpanderInterface
     */
    public function createPickingListsSalesShipmentsResourceRelationshipExpander(): PickingListsSalesShipmentsResourceRelationshipExpanderInterface
    {
        return new PickingListsSalesShipmentsResourceRelationshipExpander(
            $this->createPickingListItemResourceFilter(),
            $this->createSalesShipmentResourceRelationshipReader(),
        );
    }

    /**
     * @return \Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface
     */
    public function createPickingListItemResourceFilter(): PickingListItemResourceFilterInterface
    {
        return new PickingListItemResourceFilter();
    }

    /**
     * @return \Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Reader\SalesShipmentResourceRelationshipReaderInterface
     */
    public function createSalesShipmentResourceRelationshipReader(): SalesShipmentResourceRelationshipReaderInterface
    {
        return new SalesShipmentResourceRelationshipReader($this->getShipmentsBackendApiResource());
    }

    /**
     * @return \Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Dependency\Resource\PickingListsShipmentsBackendResourceRelationshipToShipmentsBackendApiResourceInterface
     */
    public function getShipmentsBackendApiResource(): PickingListsShipmentsBackendResourceRelationshipToShipmentsBackendApiResourceInterface
    {
        return $this->getProvidedDependency(PickingListsShipmentsBackendResourceRelationshipDependencyProvider::RESOURCE_SHIPMENTS_BACKEND_API);
    }
}
