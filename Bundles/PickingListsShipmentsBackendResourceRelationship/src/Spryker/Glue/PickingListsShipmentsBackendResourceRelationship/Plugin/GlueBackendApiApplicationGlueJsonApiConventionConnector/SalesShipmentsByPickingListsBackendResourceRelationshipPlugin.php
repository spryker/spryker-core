<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\PickingListsShipmentsBackendResourceRelationshipFactory getFactory()
 */
class SalesShipmentsByPickingListsBackendResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * @uses \Spryker\Glue\ShipmentsBackendApi\ShipmentsBackendApiConfig::RESOURCE_SALES_SHIPMENTS
     *
     * @var string
     */
    protected const RESOURCE_SALES_SHIPMENTS = 'sales-shipments';

    /**
     * {@inheritDoc}
     * - Adds `sales-shipments` resources as a relationship to `picking-list-items` resources.
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
            ->createPickingListsSalesShipmentsResourceRelationshipExpander()
            ->addPickingListItemsSalesShipmentsRelationships($resources, $glueRequestTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns resource type for sales shipments.
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return static::RESOURCE_SALES_SHIPMENTS;
    }
}
