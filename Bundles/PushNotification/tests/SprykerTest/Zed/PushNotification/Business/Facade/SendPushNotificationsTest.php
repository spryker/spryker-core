<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotification\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Spryker\Zed\PushNotification\PushNotificationDependencyProvider;
use SprykerTest\Zed\PushNotification\PushNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PushNotification
 * @group Business
 * @group Facade
 * @group SendPushNotificationsTest
 * Add your own group annotations below this line
 */
class SendPushNotificationsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PushNotification\PushNotificationBusinessTester
     */
    protected PushNotificationBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensurePushNotificationTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testSendPushNotificationsShouldReturnSentPushNotificationsWhenSentIsSuccessful(): void
    {
        // Arrange
        $pushNotificationTransfer = $this->tester->havePushNotification();

        $this->tester->setDependency(
            PushNotificationDependencyProvider::PLUGINS_PUSH_NOTIFICATION_SENDER,
            [
                $this->tester->createPushNotificationSenderPluginMock(
                    [
                        'send' => function () use ($pushNotificationTransfer): PushNotificationCollectionResponseTransfer {
                            return (new PushNotificationCollectionResponseTransfer())
                                ->setPushNotifications(new ArrayObject([$pushNotificationTransfer]))
                                ->setErrors(new ArrayObject([]));
                        },
                    ],
                ),
            ],
        );
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationCollectionResponseTransfer = $pushNotificationFacade->sendPushNotifications();

        // Assert
        $this->assertCount(1, $pushNotificationCollectionResponseTransfer->getPushNotifications());
        $this->assertEmpty($pushNotificationCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testSendPushNotificationsShouldCreateDeliveryLogWhenSentIsSuccessful(): void
    {
        // Arrange
        $pushNotificationTransfer = $this->tester->havePushNotification();
        $pushNotificationSubscriptionTransfer = $this->tester->havePushNotificationSubscription(
            [],
            [],
            [
                PushNotificationGroupTransfer::NAME => $pushNotificationTransfer->getGroupOrFail()->getNameOrFail(),
            ],
        );

        $this->tester->setDependency(
            PushNotificationDependencyProvider::PLUGINS_PUSH_NOTIFICATION_SENDER,
            [
                $this->tester->createPushNotificationSenderPluginMockWithExtendedPushNotificationTransfer(
                    $pushNotificationTransfer,
                    $pushNotificationSubscriptionTransfer,
                ),
            ],
        );

        // Act
        $pushNotificationCollectionResponseTransfer = $this->tester->getFacade()->sendPushNotifications();

        // Assert
        $pushNotificationSubscriptionDeliveryLogEntity = $this->tester->findPushNotificationSubscriptionDeliveryLogEntity(
            $pushNotificationTransfer->getIdPushNotificationOrFail(),
            $pushNotificationSubscriptionTransfer->getIdPushNotificationSubscriptionOrFail(),
        );
        $this->assertNotEmpty($pushNotificationSubscriptionDeliveryLogEntity);
    }

    /**
     * @return void
     */
    public function testSendPushNotificationsShouldReturnErrorsWhenSentIsNotSuccessful(): void
    {
        // Arrange
        $this->tester->setDependency(
            PushNotificationDependencyProvider::PLUGINS_PUSH_NOTIFICATION_SENDER,
            [
                $this->tester->createPushNotificationSenderPluginMock(
                    [
                        'send' => function (): PushNotificationCollectionResponseTransfer {
                            return (new PushNotificationCollectionResponseTransfer())
                                ->setPushNotifications(new ArrayObject([]))
                                ->setErrors(new ArrayObject([new ErrorTransfer()]));
                        },
                    ],
                ),
            ],
        );
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationCollectionResponseTransfer = $pushNotificationFacade->sendPushNotifications();

        // Assert
        $this->assertEmpty($pushNotificationCollectionResponseTransfer->getPushNotifications());
        $this->assertCount(1, $pushNotificationCollectionResponseTransfer->getErrors());
    }
}
