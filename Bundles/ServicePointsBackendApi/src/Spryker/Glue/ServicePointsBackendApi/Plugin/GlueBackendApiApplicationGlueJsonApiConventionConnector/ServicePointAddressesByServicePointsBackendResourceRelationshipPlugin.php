<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

/**
 * @method \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiFactory getFactory()
 */
class ServicePointAddressesByServicePointsBackendResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `service-point-addresses` resources as a relationship to `service-points` resources.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $resources
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addRelationships(array $resources, GlueRequestTransfer $glueRequestTransfer): void
    {
        $this->getFactory()
            ->createServicePointRelationshipExpander()
            ->addServicePointAddressesResourceRelationships($resources, $glueRequestTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns resource type for service point addresses.
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINT_ADDRESSES;
    }
}
