<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer;

class PushNotificationsBackendApiToPushNotificationFacadeBridge implements PushNotificationsBackendApiToPushNotificationFacadeInterface
{
    /**
     * @var \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface
     */
    protected $pushNotificationFacade;

    /**
     * @param \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface $pushNotificationFacade
     */
    public function __construct($pushNotificationFacade)
    {
        $this->pushNotificationFacade = $pushNotificationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionRequestTransfer $pushNotificationSubscriptionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionCollectionResponseTransfer
     */
    public function createPushNotificationSubscriptionCollection(
        PushNotificationSubscriptionCollectionRequestTransfer $pushNotificationSubscriptionCollectionRequestTransfer
    ): PushNotificationSubscriptionCollectionResponseTransfer {
        return $this->pushNotificationFacade->createPushNotificationSubscriptionCollection(
            $pushNotificationSubscriptionCollectionRequestTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer
     */
    public function getPushNotificationProviderCollection(
        PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
    ): PushNotificationProviderCollectionTransfer {
        return $this->pushNotificationFacade->getPushNotificationProviderCollection(
            $pushNotificationProviderCriteriaTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    public function createPushNotificationProviderCollection(
        PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
    ): PushNotificationProviderCollectionResponseTransfer {
        return $this->pushNotificationFacade->createPushNotificationProviderCollection(
            $pushNotificationProviderCollectionRequestTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    public function updatePushNotificationProviderCollection(
        PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
    ): PushNotificationProviderCollectionResponseTransfer {
        return $this->pushNotificationFacade->updatePushNotificationProviderCollection(
            $pushNotificationProviderCollectionRequestTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionDeleteCriteriaTransfer $pushNotificationProviderCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    public function deletePushNotificationProviderCollection(
        PushNotificationProviderCollectionDeleteCriteriaTransfer $pushNotificationProviderCollectionDeleteCriteriaTransfer
    ): PushNotificationProviderCollectionResponseTransfer {
        return $this->pushNotificationFacade->deletePushNotificationProviderCollection(
            $pushNotificationProviderCollectionDeleteCriteriaTransfer,
        );
    }
}
