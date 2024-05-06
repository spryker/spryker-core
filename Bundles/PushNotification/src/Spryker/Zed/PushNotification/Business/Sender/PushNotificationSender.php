<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Sender;

use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;
use Spryker\Zed\PushNotification\Business\Creator\PushNotificationSubscriptionDeliveryLogCreatorInterface;
use Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionDeliveryLogExtractorInterface;

class PushNotificationSender implements PushNotificationSenderInterface
{
    /**
     * @var array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationPreSendPluginInterface>
     */
    protected array $pushNotificationPreSendPlugins;

    /**
     * @var array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface>
     */
    protected array $pushNotificationSenderPlugins;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionDeliveryLogExtractorInterface
     */
    protected PushNotificationSubscriptionDeliveryLogExtractorInterface $pushNotificationSubscriptionDeliveryLogExtractor;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Creator\PushNotificationSubscriptionDeliveryLogCreatorInterface
     */
    protected PushNotificationSubscriptionDeliveryLogCreatorInterface $pushNotificationSubscriptionDeliveryLogCreator;

    /**
     * @param array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationPreSendPluginInterface> $pushNotificationPreSendPlugins
     * @param array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface> $pushNotificationSenderPlugins
     * @param \Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionDeliveryLogExtractorInterface $pushNotificationSubscriptionDeliveryLogExtractor
     * @param \Spryker\Zed\PushNotification\Business\Creator\PushNotificationSubscriptionDeliveryLogCreatorInterface $pushNotificationSubscriptionDeliveryLogCreator
     */
    public function __construct(
        array $pushNotificationPreSendPlugins,
        array $pushNotificationSenderPlugins,
        PushNotificationSubscriptionDeliveryLogExtractorInterface $pushNotificationSubscriptionDeliveryLogExtractor,
        PushNotificationSubscriptionDeliveryLogCreatorInterface $pushNotificationSubscriptionDeliveryLogCreator
    ) {
        $this->pushNotificationPreSendPlugins = $pushNotificationPreSendPlugins;
        $this->pushNotificationSenderPlugins = $pushNotificationSenderPlugins;
        $this->pushNotificationSubscriptionDeliveryLogExtractor = $pushNotificationSubscriptionDeliveryLogExtractor;
        $this->pushNotificationSubscriptionDeliveryLogCreator = $pushNotificationSubscriptionDeliveryLogCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    public function sendPushNotifications(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PushNotificationCollectionResponseTransfer {
        $pushNotificationCollectionRequestTransfer = $this->executePushNotificationPreSendPlugins(
            $pushNotificationCollectionRequestTransfer,
        );

        $pushNotificationCollectionResponseTransfer = $this->executePushNotificationSenderPlugins(
            $pushNotificationCollectionRequestTransfer,
        );

        $pushNotificationSubscriptionDeliveryLogTransfers = $this
            ->pushNotificationSubscriptionDeliveryLogExtractor
            ->extractDeliveryLogs($pushNotificationCollectionResponseTransfer->getPushNotifications());

        $this->pushNotificationSubscriptionDeliveryLogCreator->createPushNotificationSubscriptionDeliveryLogCollection(
            $pushNotificationSubscriptionDeliveryLogTransfers,
        );

        return $pushNotificationCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    protected function executePushNotificationSenderPlugins(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PushNotificationCollectionResponseTransfer {
        $pushNotificationCollectionResponseTransfer = new PushNotificationCollectionResponseTransfer();
        foreach ($this->pushNotificationSenderPlugins as $pushNotificationSenderPlugin) {
            $pluginPushNotificationCollectionResponseTransfer = $pushNotificationSenderPlugin->send(
                $pushNotificationCollectionRequestTransfer,
            );

            $pushNotificationCollectionResponseTransfer = $this
                ->extendPushNotificationCollectionResponse(
                    $pushNotificationCollectionResponseTransfer,
                    $pluginPushNotificationCollectionResponseTransfer,
                );
        }

        return $pushNotificationCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer $pushNotificationCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer $extraPushNotificationCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    protected function extendPushNotificationCollectionResponse(
        PushNotificationCollectionResponseTransfer $pushNotificationCollectionResponseTransfer,
        PushNotificationCollectionResponseTransfer $extraPushNotificationCollectionResponseTransfer
    ): PushNotificationCollectionResponseTransfer {
        $pushNotificationCollectionResponseTransfer = $this->extendPushNotificationCollection(
            $pushNotificationCollectionResponseTransfer,
            $extraPushNotificationCollectionResponseTransfer,
        );

        return $this->extendPushNotificationErrors(
            $pushNotificationCollectionResponseTransfer,
            $extraPushNotificationCollectionResponseTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer $pushNotificationCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer $extraPushNotificationCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    protected function extendPushNotificationCollection(
        PushNotificationCollectionResponseTransfer $pushNotificationCollectionResponseTransfer,
        PushNotificationCollectionResponseTransfer $extraPushNotificationCollectionResponseTransfer
    ): PushNotificationCollectionResponseTransfer {
        foreach ($extraPushNotificationCollectionResponseTransfer->getPushNotifications() as $pushNotificationTransfer) {
            $pushNotificationCollectionResponseTransfer->addPushNotification($pushNotificationTransfer);
        }

        return $pushNotificationCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer $pushNotificationCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer $extraPushNotificationCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    protected function extendPushNotificationErrors(
        PushNotificationCollectionResponseTransfer $pushNotificationCollectionResponseTransfer,
        PushNotificationCollectionResponseTransfer $extraPushNotificationCollectionResponseTransfer
    ): PushNotificationCollectionResponseTransfer {
        foreach ($extraPushNotificationCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $pushNotificationCollectionResponseTransfer->addError($errorTransfer);
        }

        return $pushNotificationCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer
     */
    protected function executePushNotificationPreSendPlugins(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PushNotificationCollectionRequestTransfer {
        foreach ($this->pushNotificationPreSendPlugins as $pushNotificationPreSendPlugin) {
            $pushNotificationCollectionRequestTransfer = $pushNotificationPreSendPlugin
                ->preSend($pushNotificationCollectionRequestTransfer);
        }

        return $pushNotificationCollectionRequestTransfer;
    }
}
