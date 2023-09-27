<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsWarehousesBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\PickingListsWarehousesBackendApi\PickingListsWarehousesBackendApiFactory getFactory()
 */
class WarehousesByPickingListsBackendResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * @uses \Spryker\Glue\WarehousesBackendApi\WarehousesBackendApiConfig::RESOURCE_WAREHOUSES
     *
     * @var string
     */
    protected const RESOURCE_WAREHOUSES = 'warehouses';

    /**
     * {@inheritDoc}
     * - Adds `warehouses` resources as a relationships to `picking-lists` resources.
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
            ->createPickingListWarehouseResourceRelationshipExpander()
            ->addPickingListWarehouseRelationships($resources, $glueRequestTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns resource type for warehouses.
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return static::RESOURCE_WAREHOUSES;
    }
}
