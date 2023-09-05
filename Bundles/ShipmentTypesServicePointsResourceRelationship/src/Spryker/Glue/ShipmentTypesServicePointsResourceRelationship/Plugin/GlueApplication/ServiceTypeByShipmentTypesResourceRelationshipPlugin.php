<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\ShipmentTypesServicePointsResourceRelationshipConfig;

/**
 * @Glue({
 *      "resourceAttributesClassName": "\\Generated\\Shared\\Transfer\\RestServiceTypesAttributesTransfer"
 *  })
 *
 * @method \Spryker\Glue\ShipmentTypesServicePointsResourceRelationship\ShipmentTypesServicePointsResourceRelationshipFactory getFactory()
 */
class ServiceTypeByShipmentTypesResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `service-types` resource as relationship by `shipment-types`.
     *
     * @api
     *
     * @param list<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createServiceTypeByShipmentTypesExpander()
            ->addResourceRelationships($resources, $restRequest);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return ShipmentTypesServicePointsResourceRelationshipConfig::RESOURCE_SERVICE_TYPES;
    }
}
