<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Plugin\GlueStorefrontApiApplication;

use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\AbstractResourcePlugin;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\StoresApi\Controller\StoresResourceController;
use Spryker\Glue\StoresApi\StoresApiConfig;

class StoresResource extends AbstractResourcePlugin implements JsonApiResourceInterface
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
        return StoresApiConfig::RESOURCE_STORES;
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
        return StoresResourceController::class;
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
                    ->setAttributes('\Generated\Shared\Transfer\ApiStoreAttributesTransfer')
                    ->setIsSingularResponse(true),
            )
            ->setGetCollection(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getCollectionAction')
                    ->setAttributes('\Generated\Shared\Transfer\ApiStoreAttributesTransfer')
                    ->setIsSingularResponse(false),
            );
    }
}
