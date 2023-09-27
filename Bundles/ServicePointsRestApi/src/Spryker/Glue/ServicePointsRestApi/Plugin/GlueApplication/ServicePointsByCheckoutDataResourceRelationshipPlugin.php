<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;

/**
 * @Glue({
 *     "resourceAttributesClassName": "\\Generated\\Shared\\Transfer\\RestServicePointsAttributesTransfer"
 * })
 *
 * @method \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiFactory getFactory()
 */
class ServicePointsByCheckoutDataResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `service-points` resources as a relationship to `checkout-data` resources.
     * - Relationship applies only if `RestCheckoutDataTransfer` is provided as resource's payload.
     * - Uses `RestCheckoutDataTransfer.servicePoints` from the resource's payload.
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
            ->createServicePointByCheckoutDataResourceRelationshipExpander()
            ->addServicePointsResourceRelationships($resources);
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
        return ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS;
    }
}
