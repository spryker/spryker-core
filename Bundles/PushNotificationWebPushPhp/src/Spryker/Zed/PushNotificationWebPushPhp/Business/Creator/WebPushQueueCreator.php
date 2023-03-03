<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToSubscriptionInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\Service\PushNotificationWebPushPhpToUtilEncodingServiceInterface;

class WebPushQueueCreator implements WebPushQueueCreatorInterface
{
    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToSubscriptionInterface
     */
    protected PushNotificationWebPushPhpToSubscriptionInterface $pushNotificationWebPushPhpToSubscriptionAdapter;

    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface
     */
    protected PushNotificationWebPushPhpToWebPushInterface $webPush;

    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Dependency\Service\PushNotificationWebPushPhpToUtilEncodingServiceInterface
     */
    protected PushNotificationWebPushPhpToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToSubscriptionInterface $pushNotificationWebPushPhpToSubscriptionAdapter
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface $webPush
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Dependency\Service\PushNotificationWebPushPhpToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        PushNotificationWebPushPhpToSubscriptionInterface $pushNotificationWebPushPhpToSubscriptionAdapter,
        PushNotificationWebPushPhpToWebPushInterface $webPush,
        PushNotificationWebPushPhpToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->pushNotificationWebPushPhpToSubscriptionAdapter = $pushNotificationWebPushPhpToSubscriptionAdapter;
        $this->webPush = $webPush;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface
     */
    public function queuePushNotifications(ArrayObject $pushNotificationTransfers): PushNotificationWebPushPhpToWebPushInterface
    {
        foreach ($pushNotificationTransfers as $pushNotificationTransfer) {
            $this->queuePushNotificationSubscriptions($pushNotificationTransfer);
        }

        return $this->webPush;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     *
     * @return void
     */
    protected function queuePushNotificationSubscriptions(PushNotificationTransfer $pushNotificationTransfer): void
    {
        foreach ($pushNotificationTransfer->getSubscriptions() as $pushNotificationSubscriptionTransfer) {
            $subscription = $this->pushNotificationWebPushPhpToSubscriptionAdapter->create(
                $pushNotificationSubscriptionTransfer->getPayload(),
            );
            $this->webPush->queueNotification(
                $subscription,
                $this->utilEncodingService->encodeJson($pushNotificationTransfer->getPayload(), JSON_UNESCAPED_UNICODE),
                [],
                [],
                $pushNotificationTransfer->getIdPushNotificationOrFail(),
                $pushNotificationSubscriptionTransfer->getIdPushNotificationSubscriptionOrFail(),
            );
        }
    }
}
