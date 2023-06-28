<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\ApiPushNotificationProvidersAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\Backend\AbstractResourcePlugin;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\PushNotificationsBackendApi\Controller\PushNotificationProvidersResourceController;
use Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig;

class PushNotificationProvidersBackendResourcePlugin extends AbstractResourcePlugin implements JsonApiResourceInterface
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
        return PushNotificationsBackendApiConfig::RESOURCE_PUSH_NOTIFICATION_PROVIDERS;
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
        return PushNotificationProvidersResourceController::class;
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
            ->setGetCollection(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getCollectionAction')
                    ->setAttributes(ApiPushNotificationProvidersAttributesTransfer::class),
            )
            ->setGet(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getAction')
                    ->setAttributes(ApiPushNotificationProvidersAttributesTransfer::class),
            )
            ->setPost(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('postAction')
                    ->setAttributes(ApiPushNotificationProvidersAttributesTransfer::class),
            )
            ->setPatch(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('patchAction')
                    ->setAttributes(ApiPushNotificationProvidersAttributesTransfer::class),
            )
            ->setDelete(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('deleteAction')
                    ->setAttributes(ApiPushNotificationProvidersAttributesTransfer::class),
            );
    }
}
