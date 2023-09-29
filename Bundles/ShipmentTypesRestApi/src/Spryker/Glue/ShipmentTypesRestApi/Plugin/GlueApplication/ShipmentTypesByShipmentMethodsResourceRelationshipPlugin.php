<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig;

/**
 * @Glue({
 *     "resourceAttributesClassName": "\\Generated\\Shared\\Transfer\\RestShipmentTypesAttributesTransfer"
 * })
 *
 * @method \Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiFactory getFactory()
 */
class ShipmentTypesByShipmentMethodsResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inehritDoc}
     * - Adds `shipment-types` resources as a relationship to `shipment-methods` resources.
     * - Relationship applies only if `ShipmentMethodTransfer` is provided as resource's payload.
     * - Uses `ShipmentMethodTransfer::shipmentType` from the resource's payload.
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
            ->createShipmentTypeByShipmentMethodResourceRelationshipExpander()
            ->addShipmentTypesResourceRelationships($resources);
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
        return ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES;
    }
}
