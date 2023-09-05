<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesServicePointsResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Dependency\Resource\ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceInterface;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Builder\ServiceTypeResourceBuilder;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Builder\ServiceTypeResourceBuilderInterface;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Expander\ShipmentTypeServiceTypeResourceRelationshipExpander;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Expander\ShipmentTypeServiceTypeResourceRelationshipExpanderInterface;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Reader\ServiceTypeReader;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Reader\ServiceTypeReaderInterface;

class ShipmentTypesServicePointsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Expander\ShipmentTypeServiceTypeResourceRelationshipExpanderInterface
     */
    public function createServiceTypeByShipmentTypesExpander(): ShipmentTypeServiceTypeResourceRelationshipExpanderInterface
    {
        return new ShipmentTypeServiceTypeResourceRelationshipExpander(
            $this->createServiceTypeReader(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Reader\ServiceTypeReaderInterface
     */
    public function createServiceTypeReader(): ServiceTypeReaderInterface
    {
        return new ServiceTypeReader(
            $this->getServicePointsRestApiResource(),
            $this->createServiceTypeResourceBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Processor\Builder\ServiceTypeResourceBuilderInterface
     */
    public function createServiceTypeResourceBuilder(): ServiceTypeResourceBuilderInterface
    {
        return new ServiceTypeResourceBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Dependency\Resource\ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceInterface
     */
    public function getServicePointsRestApiResource(): ShipmentTypesServicePointsResourceRelationshipToServicePointsRestApiResourceInterface
    {
        return $this->getProvidedDependency(ShipmentTypesServicePointsResourceRelationshipDependencyProvider::RESOURCE_SERVICE_POINTS_REST_API);
    }
}
