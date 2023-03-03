<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Dependency\Facade;

use Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;

class PushNotificationWebPushPhpToPushNotificationFacadeBridge implements PushNotificationWebPushPhpToPushNotificationFacadeInterface
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
     * @param \Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer
     */
    public function getPushNotificationProviderCollection(
        PushNotificationProviderCriteriaTransfer $pushNotificationProviderCriteriaTransfer
    ): PushNotificationProviderCollectionTransfer {
        return $this
            ->pushNotificationFacade
            ->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionResponseTransfer
     */
    public function createPushNotificationProviderCollection(
        PushNotificationProviderCollectionRequestTransfer $pushNotificationProviderCollectionRequestTransfer
    ): PushNotificationProviderCollectionResponseTransfer {
        return $this
            ->pushNotificationFacade
            ->createPushNotificationProviderCollection($pushNotificationProviderCollectionRequestTransfer);
    }
}
