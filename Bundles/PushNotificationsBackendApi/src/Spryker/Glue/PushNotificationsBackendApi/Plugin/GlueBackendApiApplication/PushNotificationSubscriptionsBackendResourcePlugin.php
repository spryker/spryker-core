<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\Backend\AbstractResourcePlugin;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\PushNotificationsBackendApi\Controller\PushNotificationSubscriptionResourceController;
use Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig;

class PushNotificationSubscriptionsBackendResourcePlugin extends AbstractResourcePlugin implements JsonApiResourceInterface
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
        return PushNotificationsBackendApiConfig::RESOURCE_PUSH_NOTIFICATION_SUBSCRIPTIONS;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @uses \Spryker\Glue\PushNotificationsBackendApi\Controller\TokenResourceController
     *
     * @return string
     */
    public function getController(): string
    {
        return PushNotificationSubscriptionResourceController::class;
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
                    ->setAttributes('\Generated\Shared\Transfer\ApiPushNotificationSubscriptionAttributes'),
            );
    }
}
