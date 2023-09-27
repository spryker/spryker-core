<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\Backend\AbstractResourcePlugin;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\WarehouseUsersBackendApi\Controller\WarehouseUserAssignmentsResourceController;
use Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig;

class WarehouseUserAssignmentsBackendResourcePlugin extends AbstractResourcePlugin implements JsonApiResourceInterface
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
        return WarehouseUsersBackendApiConfig::RESOURCE_TYPE_WAREHOUSE_USER_ASSIGNMENTS;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @uses \Spryker\Glue\WarehouseUsersBackendApi\Controller\WarehouseUserAssignmentResourceController
     *
     * @return string
     */
    public function getController(): string
    {
        return WarehouseUserAssignmentsResourceController::class;
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
            ->setGet(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getAction')
                    ->setAttributes(WarehouseUserAssignmentsBackendApiAttributesTransfer::class),
            )->setGetCollection(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getCollectionAction')
                    ->setAttributes(WarehouseUserAssignmentsBackendApiAttributesTransfer::class),
            )->setPost(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('postAction')
                    ->setAttributes(WarehouseUserAssignmentsBackendApiAttributesTransfer::class),
            )->setPatch(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('patchAction')
                    ->setAttributes(WarehouseUserAssignmentsBackendApiAttributesTransfer::class),
            )->setDelete(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('deleteAction'),
            );
    }
}
