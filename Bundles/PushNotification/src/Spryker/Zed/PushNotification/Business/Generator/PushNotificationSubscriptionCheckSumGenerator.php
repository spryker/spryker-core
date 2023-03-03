<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Generator;

use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface;
use Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilTextServiceInterface;

class PushNotificationSubscriptionCheckSumGenerator implements PushNotificationSubscriptionCheckSumGeneratorInterface
{
    /**
     * @uses \Spryker\Service\UtilText\Model\Hash::MD5
     *
     * @var string
     */
    protected const MD5 = 'md5';

    /**
     * @var \Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface
     */
    protected PushNotificationToUtilEncodingServiceInterface $pushNotificationToUtilEncodingService;

    /**
     * @var \Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilTextServiceInterface
     */
    protected PushNotificationToUtilTextServiceInterface $pushNotificationToUtilTextService;

    /**
     * @param \Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceInterface $pushNotificationToUtilEncodingService
     * @param \Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilTextServiceInterface $pushNotificationToUtilTextService
     */
    public function __construct(
        PushNotificationToUtilEncodingServiceInterface $pushNotificationToUtilEncodingService,
        PushNotificationToUtilTextServiceInterface $pushNotificationToUtilTextService
    ) {
        $this->pushNotificationToUtilEncodingService = $pushNotificationToUtilEncodingService;
        $this->pushNotificationToUtilTextService = $pushNotificationToUtilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return string
     */
    public function generatePayloadChecksum(PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer): string
    {
        $encodedPayload = $this->pushNotificationToUtilEncodingService->encodeJson(
            $pushNotificationSubscriptionTransfer->getPayload(),
        );

        return $this->pushNotificationToUtilTextService->hashValue($encodedPayload, static::MD5);
    }
}
