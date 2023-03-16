<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\Backend\AbstractResourcePlugin;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Controller\WarehouseTokensResourceController;
use Spryker\Glue\WarehouseOauthBackendApi\WarehouseOauthBackendApiConfig;

class WarehouseTokensBackendResourcePlugin extends AbstractResourcePlugin implements ResourceInterface
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
        return WarehouseOauthBackendApiConfig::RESOURCE_WAREHOUSE_TOKENS;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @uses \Spryker\Glue\WarehouseOauthBackendApi\Controller\WarehouseTokensResourceController
     *
     * @return string
     */
    public function getController(): string
    {
        return WarehouseTokensResourceController::class;
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
        return (new GlueResourceMethodCollectionTransfer())->setPost(
            (new GlueResourceMethodConfigurationTransfer())->setAction('postAction'),
        );
    }
}
