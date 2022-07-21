<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApi\Plugin;

use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\Backend\AbstractResourcePlugin;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface;
use Spryker\Glue\OauthBackendApi\Controller\TokenResourceController;
use Spryker\Glue\OauthBackendApi\OauthBackendApiConfig;

class TokenResource extends AbstractResourcePlugin implements RestResourceInterface
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
        return OauthBackendApiConfig::RESOURCE_TOKEN;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @uses \Spryker\Glue\OauthBackendApi\Controller\TokenResourceController
     *
     * @return string
     */
    public function getController(): string
    {
        return TokenResourceController::class;
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
            ->setPost(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('postAction')
                    ->setAttributes('\Generated\Shared\Transfer\ApiTokenAttributesTransfer'),
            );
    }
}
