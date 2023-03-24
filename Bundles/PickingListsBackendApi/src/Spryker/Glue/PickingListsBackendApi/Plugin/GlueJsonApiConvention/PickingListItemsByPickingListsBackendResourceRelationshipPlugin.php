<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Plugin\GlueJsonApiConvention;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;

/**
 * @method \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiFactory getFactory()
 */
class PickingListItemsByPickingListsBackendResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `picking-list-items` resources as a relationship to `picking-lists` resources.
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
            ->createPickingListRelationshipExpander()
            ->addPickingListItemsResourceRelationships($resources, $glueRequestTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns resource type for picking list items.
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS;
    }
}
