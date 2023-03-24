<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\AbstractResourcePlugin;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceWithParentPluginInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\PickingListsBackendApi\Controller\PickingListItemResourceController;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;

class PickingListItemsBackendResourcePlugin extends AbstractResourcePlugin implements JsonApiResourceInterface, ResourceWithParentPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getType(): string
    {
        return PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getParentResourceType(): string
    {
        return PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return PickingListItemResourceController::class;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer
     */
    public function getDeclaredMethods(): GlueResourceMethodCollectionTransfer
    {
        return (new GlueResourceMethodCollectionTransfer())
            ->setPatch(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('patchAction')
                    ->setAttributes(ApiPickingListItemsAttributesTransfer::class),
            );
    }
}
