<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Sender;

use ArrayObject;
use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Builder\MessageSentReportIdentifierBuilderInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreatorInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\WebPushQueueCreatorInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Expander\PushNotificationSubscriptionDeliveryLogExpanderInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Business\Filter\PushNotificationFilterInterface;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface;
use Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig;

class PushNotificationSender implements PushNotificationSenderInterface
{
    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Business\Filter\PushNotificationFilterInterface
     */
    protected PushNotificationFilterInterface $pushNotificationFilter;

    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreatorInterface
     */
    protected ErrorCreatorInterface $errorCreator;

    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\WebPushQueueCreatorInterface
     */
    protected WebPushQueueCreatorInterface $webPushQueueCreator;

    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Business\Expander\PushNotificationSubscriptionDeliveryLogExpanderInterface
     */
    protected PushNotificationSubscriptionDeliveryLogExpanderInterface $pushNotificationSubscriptionDeliveryLogExpander;

    /**
     * @var \Spryker\Zed\PushNotificationWebPushPhp\Business\Builder\MessageSentReportIdentifierBuilderInterface
     */
    protected MessageSentReportIdentifierBuilderInterface $messageSentReportIdentifierBuilder;

    /**
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Business\Filter\PushNotificationFilterInterface $pushNotificationFilter
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\ErrorCreatorInterface $errorCreator
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Business\Creator\WebPushQueueCreatorInterface $webPushQueueCreator
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Business\Expander\PushNotificationSubscriptionDeliveryLogExpanderInterface $pushNotificationSubscriptionDeliveryLogExpander
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Business\Builder\MessageSentReportIdentifierBuilderInterface $messageSentReportIdentifierBuilder
     */
    public function __construct(
        PushNotificationFilterInterface $pushNotificationFilter,
        ErrorCreatorInterface $errorCreator,
        WebPushQueueCreatorInterface $webPushQueueCreator,
        PushNotificationSubscriptionDeliveryLogExpanderInterface $pushNotificationSubscriptionDeliveryLogExpander,
        MessageSentReportIdentifierBuilderInterface $messageSentReportIdentifierBuilder
    ) {
        $this->pushNotificationFilter = $pushNotificationFilter;
        $this->errorCreator = $errorCreator;
        $this->webPushQueueCreator = $webPushQueueCreator;
        $this->pushNotificationSubscriptionDeliveryLogExpander = $pushNotificationSubscriptionDeliveryLogExpander;
        $this->messageSentReportIdentifierBuilder = $messageSentReportIdentifierBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    public function sendNotifications(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PushNotificationCollectionResponseTransfer {
        $pushNotificationTransfers = $this->pushNotificationFilter->filterPushNotificationCollectionByProviderName(
            $pushNotificationCollectionRequestTransfer->getPushNotifications(),
            PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME,
        );

        $pushNotificationQueue = $this->webPushQueueCreator->queuePushNotifications($pushNotificationTransfers);

        return $this->sendPushNotifications($pushNotificationQueue, $pushNotificationTransfers);
    }

    /**
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface $webPushPhpQueue
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer
     */
    protected function sendPushNotifications(
        PushNotificationWebPushPhpToWebPushInterface $webPushPhpQueue,
        ArrayObject $pushNotificationTransfers
    ): PushNotificationCollectionResponseTransfer {
        $pushNotificationCollectionResponseTransfer = new PushNotificationCollectionResponseTransfer();
        $pushNotificationTransfersIndexedByIdPushNotification = $this->getPushNotificationTransfersIndexedByIdPushNotification(
            $pushNotificationTransfers,
        );
        $sentPushNotificationTransfersIndexedByIdPushNotification = [];
        /** @var \Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\MessageSentReport $report */
        foreach ($webPushPhpQueue->flush() as $report) {
            /** @var \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer */
            $pushNotificationTransfer = $pushNotificationTransfersIndexedByIdPushNotification[$report->getPushNotificationIdentifier()];
            if (!$report->isSuccess()) {
                $errorTransfer = $this->errorCreator->createErrorTransfer(
                    $this->messageSentReportIdentifierBuilder->builderIdentifier($report),
                    $report->getReason(),
                );
                $pushNotificationCollectionResponseTransfer->addError($errorTransfer);

                continue;
            }
            $pushNotificationSubscriptionDeliveryLog = $this->createPushNotificationSubscriptionDeliveryLogTransfer(
                $report->getPushNotificationIdentifier(),
                $report->getPushNotificationSubscriptionIdentifier(),
            );
            $pushNotificationTransfer = $this->pushNotificationSubscriptionDeliveryLogExpander
                ->extendPushNotificationPushNotificationSubscriptionDeliveryLogs(
                    $pushNotificationTransfer,
                    $pushNotificationSubscriptionDeliveryLog,
                );
            $sentPushNotificationTransfersIndexedByIdPushNotification[$pushNotificationTransfer->getIdPushNotificationOrFail()] = $pushNotificationTransfer;
        }

        return $pushNotificationCollectionResponseTransfer->setPushNotifications(
            new ArrayObject(
                array_values($sentPushNotificationTransfersIndexedByIdPushNotification),
            ),
        );
    }

    /**
     * @param int $pushNotificationIdentifier
     * @param int $pushNotificationSubscriptionIdentifier
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer
     */
    protected function createPushNotificationSubscriptionDeliveryLogTransfer(
        int $pushNotificationIdentifier,
        int $pushNotificationSubscriptionIdentifier
    ): PushNotificationSubscriptionDeliveryLogTransfer {
        $pushNotificationTransfer = new PushNotificationTransfer();
        $pushNotificationTransfer->setIdPushNotification($pushNotificationIdentifier);

        $pushNotificationSubscriptionTransfer = new PushNotificationSubscriptionTransfer();
        $pushNotificationSubscriptionTransfer->setIdPushNotificationSubscription($pushNotificationSubscriptionIdentifier);

        return (new PushNotificationSubscriptionDeliveryLogTransfer())
            ->setPushNotification($pushNotificationTransfer)
            ->setPushNotificationSubscription($pushNotificationSubscriptionTransfer);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\PushNotificationTransfer>
     */
    protected function getPushNotificationTransfersIndexedByIdPushNotification(
        ArrayObject $pushNotificationTransfers
    ): array {
        $pushNotificationTransfersIndexedByIdPushNotification = [];
        foreach ($pushNotificationTransfers as $pushNotificationTransfer) {
            $idPushNotification = $pushNotificationTransfer->getIdPushNotificationOrFail();
            $pushNotificationTransfersIndexedByIdPushNotification[$idPushNotification] = $pushNotificationTransfer;
        }

        return $pushNotificationTransfersIndexedByIdPushNotification;
    }
}
