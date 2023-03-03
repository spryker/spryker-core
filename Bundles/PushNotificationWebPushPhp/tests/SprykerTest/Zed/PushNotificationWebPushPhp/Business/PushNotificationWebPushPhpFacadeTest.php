<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotificationWebPushPhp\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery;
use Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig;
use SprykerTest\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PushNotificationWebPushPhp
 * @group Business
 * @group Facade
 * @group PushNotificationWebPushPhpFacadeTest
 * Add your own group annotations below this line
 */
class PushNotificationWebPushPhpFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\PushNotificationWebPushPhp\Business\Validator\PushNotificationSubscriptionPayloadStructureValidator::GLOSSARY_KEY_VALIDATION_INVALID_PAYLOAD_STRUCTURE
     *
     * @var string
     */
    protected const MESSAGE_INVALID_PAYLOAD_STRUCTURE = 'push_notification_web_push_php.validation.error.invalid_payload_structure';

    /**
     * @var \SprykerTest\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpBusinessTester
     */
    protected PushNotificationWebPushPhpBusinessTester $tester;

    /**
     * @dataProvider getInvalidPushNotificationSubscriptionsProvider
     *
     * @param array<\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return void
     */
    public function testValidateSubscriptionsShouldReturnErrorsWhenInvalidPushNotificationSubscriptionsGiven(
        array $pushNotificationSubscriptionTransfers
    ): void {
        // Arrange
        /** @var \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpFacadeInterface $pushNotificationWebPushPhpFacade */
        $pushNotificationWebPushPhpFacade = $this->tester->getFacade();

        // Act
        $pushNotificationSubscriptionCollectionResponseTransfer = $pushNotificationWebPushPhpFacade->validateSubscriptions(
            new ArrayObject($pushNotificationSubscriptionTransfers),
        );

        // Assert
        $this->assertCount(
            count($pushNotificationSubscriptionTransfers),
            $pushNotificationSubscriptionCollectionResponseTransfer->getErrors(),
        );
        $this->assertSame(
            static::MESSAGE_INVALID_PAYLOAD_STRUCTURE,
            $pushNotificationSubscriptionCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @dataProvider getValidPushNotificationSubscriptionsProvider
     *
     * @param array<\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return void
     */
    public function testValidateSubscriptionsShouldReturnNoErrorsWhenValidPushNotificationSubscriptionsGiven(
        array $pushNotificationSubscriptionTransfers
    ): void {
        // Arrange
        /** @var \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpFacadeInterface $pushNotificationWebPushPhpFacade */
        $pushNotificationWebPushPhpFacade = $this->tester->getFacade();

        // Act
        $pushNotificationSubscriptionCollectionResponseTransfer = $pushNotificationWebPushPhpFacade->validateSubscriptions(
            new ArrayObject($pushNotificationSubscriptionTransfers),
        );

        // Assert
        $this->assertEmpty($pushNotificationSubscriptionCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testSendNotificationShouldSendApplicableNotificationsOnlyWhenApplicableAndNonApplicablePushNotificationsGiven(): void
    {
        // Arrange
        $applicablePushNotificationTransfer = $this->tester->createApplicablePushNotification();
        $notApplicablePushNotificationTransfer = $this->tester->createNotApplicablePushNotification();
        $pushNotificationSubscriptionTransfer = $this->tester->havePushNotificationSubscription(
            [
                PushNotificationSubscriptionTransfer::PAYLOAD => [
                    PushNotificationWebPushPhpBusinessTester::PAYLOAD_KEY_ENDPOINT => PushNotificationWebPushPhpBusinessTester::TEST_ENDPOINT,
                ],
            ],
            [
                PushNotificationProviderTransfer::NAME => $applicablePushNotificationTransfer->getProviderOrFail()->getNameOrFail(),
            ],
            [
                PushNotificationGroupTransfer::NAME => $applicablePushNotificationTransfer->getGroupOrFail()->getNameOrFail(),
            ],
        );
        $applicablePushNotificationTransfer->addSubscription($pushNotificationSubscriptionTransfer);
        $pushNotificationCollectionRequestTransfer = (new PushNotificationCollectionRequestTransfer())
            ->setPushNotifications(
                new ArrayObject([$applicablePushNotificationTransfer, $notApplicablePushNotificationTransfer]),
            );
        $this->tester->mockWebPusherWithOneMessage(
            $applicablePushNotificationTransfer->getIdPushNotificationOrFail(),
            $pushNotificationSubscriptionTransfer->getIdPushNotificationSubscriptionOrFail(),
        );

        /** @var \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpFacadeInterface $pushNotificationWebPushPhpFacade */
        $pushNotificationWebPushPhpFacade = $this->tester->getFacade();

        // Act
        $pushNotificationCollectionResponseTransfer = $pushNotificationWebPushPhpFacade->sendNotifications(
            $pushNotificationCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $pushNotificationCollectionResponseTransfer->getPushNotifications());
    }

    /**
     * @return void
     */
    public function testInstallWebPushPhpProviderShouldCreatePushNotificationProviderWhenWebPushPhpProviderDoesNotExist(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty($this->tester->createPushNotificationProviderQuery());
        /** @var \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpFacadeInterface $pushNotificationWebPushPhpFacade */
        $pushNotificationWebPushPhpFacade = $this->tester->getFacade();

        // Act
        $pushNotificationWebPushPhpFacade->installWebPushPhpProvider();

        // Assert
        $this->assertNotNull(
            $this->tester->findPushNotificationProviderEntityByName(
                PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME,
            ),
        );
    }

    /**
     * @return void
     */
    public function testInstallWebPushPhpProviderShouldNotDuplicatePushNotificationProviderWhenWebPushPhpProviderAlreadyExists(): void
    {
        // Arrange
        $this->tester->havePushNotificationProvider(
            [
                PushNotificationProviderTransfer::NAME => PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME,
            ],
        );
        /** @var \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpFacadeInterface $pushNotificationWebPushPhpFacade */
        $pushNotificationWebPushPhpFacade = $this->tester->getFacade();

        // Act
        $pushNotificationWebPushPhpFacade->installWebPushPhpProvider();

        // Assert
        $this->assertCount(
            1,
            SpyPushNotificationProviderQuery::create()
                ->filterByName(PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME)
                ->find(),
        );
    }

    /**
     * @return void
     */
    public function testValidatePayloadLengthShouldReturnNoErrorsWhenValidPushNotificationsGiven(): void
    {
        // Arrange
        $pushNotificationTransfer = $this->tester->createValidPushNotificationTransfer();
        /** @var \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpFacadeInterface $pushNotificationWebPushPhpFacade */
        $pushNotificationWebPushPhpFacade = $this->tester->getFacade();

        // Act
        $pushNotificationCollectionResponseTransfer = $pushNotificationWebPushPhpFacade->validatePayloadLength(
            new ArrayObject([$pushNotificationTransfer]),
        );

        // Assert
        $this->assertEmpty($pushNotificationCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidatePayloadLengthShouldReturnErrorsWhenInvalidPushNotificationsGiven(): void
    {
        // Arrange
        $pushNotificationTransfer = $this->tester->createInvalidPushNotificationTransfer();
        /** @var \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpFacadeInterface $pushNotificationWebPushPhpFacade */
        $pushNotificationWebPushPhpFacade = $this->tester->getFacade();

        // Act
        $pushNotificationCollectionResponseTransfer = $pushNotificationWebPushPhpFacade->validatePayloadLength(
            new ArrayObject([$pushNotificationTransfer]),
        );

        // Assert
        $this->assertCount(1, $pushNotificationCollectionResponseTransfer->getErrors());
    }

    /**
     * @return array<int, array<int, array<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>>>
     */
    public function getInvalidPushNotificationSubscriptionsProvider(): array
    {
        $pushNotificationProviderTransfer = (new PushNotificationProviderTransfer())
            ->setName(PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME);

        return [
            [
                [
                    (new PushNotificationSubscriptionTransfer())
                        ->setProvider($pushNotificationProviderTransfer)
                        ->setPayload([]),
                    (new PushNotificationSubscriptionTransfer())
                        ->setProvider($pushNotificationProviderTransfer)
                        ->setPayload(['endpoint' => '']),
                    (new PushNotificationSubscriptionTransfer())
                        ->setProvider($pushNotificationProviderTransfer)
                        ->setPayload(['endpoint' => null]),
                ],
            ],
            [
                [
                    (new PushNotificationSubscriptionTransfer())
                        ->setProvider($pushNotificationProviderTransfer)
                        ->setPayload(
                            [
                                'endpoint' => 'https://foo.bar',
                                'publicKey' => '35UVM4Ryfe678ZKagaMg',
                            ],
                        ),
                ],
            ],
            [
                [
                    (new PushNotificationSubscriptionTransfer())
                        ->setProvider($pushNotificationProviderTransfer)
                        ->setPayload(
                            [
                                'endpoint' => 'https://foo.bar',
                                'authToken' => '9k1ITrSlKJwIq1Hqp9Zy==',
                            ],
                        ),
                ],
            ],
            [
                [
                    (new PushNotificationSubscriptionTransfer())
                        ->setProvider($pushNotificationProviderTransfer)
                        ->setPayload(
                            [
                                'endpoint' => 'https://foo.bar',
                                'keys' => [
                                    'p256dh' => 'DdLl3eMWXjhiucAuz7te',
                                ],
                            ],
                        ),
                ],
            ],
            [
                [
                    (new PushNotificationSubscriptionTransfer())
                        ->setProvider($pushNotificationProviderTransfer)
                        ->setPayload(
                            [
                                'endpoint' => 'https://foo.bar',
                                'keys' => [
                                    'auth' => 'bSGDqsFUn6XuKwIVjTgE',
                                ],
                            ],
                        ),
                ],
            ],
        ];
    }

    /**
     * @return array<int, array<int, array<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>>>
     */
    public function getValidPushNotificationSubscriptionsProvider(): array
    {
        $pushNotificationProviderTransfer = (new PushNotificationProviderTransfer())
            ->setName(PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME);

        return [
            [
                [
                    (new PushNotificationSubscriptionTransfer())
                        ->setProvider($pushNotificationProviderTransfer)
                        ->setPayload(['endpoint' => 'https://google.com']),
                ],
            ],
            [
                [
                    (new PushNotificationSubscriptionTransfer())
                        ->setProvider($pushNotificationProviderTransfer)
                        ->setPayload(
                            [
                                'endpoint' => 'https://google.com',
                                'publicKey' => 'QswARiAy84kLHJxHgMNi',
                                'authToken' => 'ExFMmvLodgQ8iMazGjcL==',
                            ],
                        ),
                ],
            ],
            [
                [
                    (new PushNotificationSubscriptionTransfer())
                        ->setProvider($pushNotificationProviderTransfer)
                        ->setPayload(
                            [
                                'endpoint' => 'https://google.com',
                                'keys' => [
                                    'p256dh' => '5yIpDn69cGsHxDzhPsXR',
                                    'auth' => '5FsC6uDJSKpbsTwqocM6',
                                ],
                            ],
                        ),
                ],
            ],
        ];
    }
}
