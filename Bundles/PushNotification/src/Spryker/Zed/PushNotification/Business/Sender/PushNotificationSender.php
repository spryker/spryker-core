<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Sender;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationCriteriaTransfer;
use Spryker\Zed\PushNotification\Business\Creator\PushNotificationSubscriptionDeliveryLogCreatorInterface;
use Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionDeliveryLogExtractorInterface;
use Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface;
use Spryker\Zed\PushNotification\PushNotificationConfig;

class PushNotificationSender implements PushNotificationSenderInterface
{
    /**
     * @var array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface>
     */
    protected array $pushNotificationSenderPlugins;

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface
     */
    protected PushNotificationRepositoryInterface $pushNotificationRepository;

    /**
     * @var \Spryker\Zed\PushNotification\PushNotificationConfig
     */
    protected PushNotificationConfig $pushNotificationConfig;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionDeliveryLogExtractorInterface
     */
    protected PushNotificationSubscriptionDeliveryLogExtractorInterface $pushNotificationSubscriptionDeliveryLogExtractor;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Creator\PushNotificationSubscriptionDeliveryLogCreatorInterface
     */
    protected PushNotificationSubscriptionDeliveryLogCreatorInterface $pushNotificationSubscriptionDeliveryLogCreator;

    /**
     * @param array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface> $pushNotificationSenderPlugins
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface $pushNotificationRepository
     * @param \Spryker\Zed\PushNotification\PushNotificationConfig $pushNotificationConfig
     * @param \Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionDeliveryLogExtractorInterface $pushNotificationSubscriptionDeliveryLogExtractor
     * @param \Spryker\Zed\PushNotification\Business\Creator\PushNotificationSubscriptionDeliveryLogCreatorInterface $pushNotificationSubscriptionDeliveryLogCreator
     */
    public function __construct(
        array $pushNotificationSenderPlugins,
        PushNotificationRepositoryInterface $pushNotificationRepository,
        PushNotificationConfig $pushNotificationConfig,
        PushNotificationSubscriptionDeliveryLogExtractorInterface $pushNotificationSubscriptionDeliveryLogExtractor,
        PushNotificationSubscriptionDeliveryLogCreatorInterface $pushNotificationSubscriptionDeliveryLogCreator
    ) {
        $this->pushNotificationSenderPlugins = $pushNotificationSenderPlugins;
        $this->pushNotificationRepository = $pushNotificationRepository;
        $this->pushNotificationConfig = $pushNotificationConfig;
        $this->pushNotificationSubscriptionDeliveryLogExtractor = $pushNotificationSubscriptionDeliveryLogExtractor;
        $this->pushNotificationSubscriptionDeliveryLogCreator = $pushNotificationSubscriptionDeliveryLogCreator;
    }

    /**
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    public function sendPushNotifications(): PushNotificationCollectionResponseTransfer
    {
        $notSentPushNotifications = $this->pushNotificationRepository->getPushNotificationCollection(
            $this->createPushNotificationCriteriaTransfer(),
        );
        $pushNotificationCollectionRequestTransfer = (new PushNotificationCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->setPushNotifications(
                $notSentPushNotifications->getPushNotifications(),
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
     * @return \Generated\Shared\Transfer\PushNotificationCriteriaTransfer
     */
    protected function createPushNotificationCriteriaTransfer(): PushNotificationCriteriaTransfer
    {
        $paginationTransfer = (new PaginationTransfer())
            ->setPage(1)
            ->setMaxPerPage($this->pushNotificationConfig->getPushNotificationSendBatchSize());

        $pushNotificationConditionsTransfer = (new PushNotificationConditionsTransfer())
            ->setNotificationSent(false);

        return (new PushNotificationCriteriaTransfer())
            ->setPushNotificationConditions($pushNotificationConditionsTransfer)
            ->setPagination($paginationTransfer);
    }
}
