<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotification;

use ArrayObject;
use Codeception\Actor;
use Codeception\Stub;
use DateInterval;
use DateTime;
use Generated\Shared\DataBuilder\PushNotificationBuilder;
use Generated\Shared\DataBuilder\PushNotificationGroupBuilder;
use Generated\Shared\DataBuilder\PushNotificationProviderBuilder;
use Generated\Shared\DataBuilder\PushNotificationSubscriptionBuilder;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionDeliveryLogTransfer;
use Generated\Shared\Transfer\PushNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Orm\Zed\PushNotification\Persistence\Map\SpyPushNotificationProviderTableMap;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLog;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLogQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\PushNotification\PHPMD)
 */
class PushNotificationBusinessTester extends Actor
{
    use _generated\PushNotificationBusinessTesterActions;

    /**
     * @var string
     */
    public const TEST_PUSH_NOTIFICATION_PROVIDER_NAME_ONE_SIGNAL = 'one-signal-test';

    /**
     * @var string
     */
    public const TEST_PUSH_NOTIFICATION_PROVIDER_NAME_WWW_GOOGLE_FIREBASE = 'www-google-firebase-test';

    /**
     * @return void
     */
    public function ensurePushNotificationTablesAreEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getPushNotificationProviderQuery());
        $this->ensureDatabaseTableIsEmpty($this->getPushNotificationSubscriptionQuery());
        $this->ensureDatabaseTableIsEmpty($this->getPushNotificationSubscriptionDeliveryLogQuery());
        $this->ensureDatabaseTableIsEmpty($this->getPushNotificationQueryQuery());
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery
     */
    public function getPushNotificationProviderQuery(): SpyPushNotificationProviderQuery
    {
        return SpyPushNotificationProviderQuery::create();
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionQuery
     */
    public function getPushNotificationSubscriptionQuery(): SpyPushNotificationSubscriptionQuery
    {
        return SpyPushNotificationSubscriptionQuery::create();
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLogQuery
     */
    public function getPushNotificationSubscriptionDeliveryLogQuery(): SpyPushNotificationSubscriptionDeliveryLogQuery
    {
        return SpyPushNotificationSubscriptionDeliveryLogQuery::create();
    }

    /**
     * @return array<\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    public function createValidPushNotificationSubscriptions(): array
    {
        $pushNotificationProviderTransfer = $this->havePushNotificationProvider(
            [
                PushNotificationProviderTransfer::NAME => 'web-push-php',
            ],
        );

        return [
            $this->createPushNotificationSubscriptionTransfer(
                [],
                [
                    PushNotificationGroupTransfer::IDENTIFIER => 1,
                    PushNotificationGroupTransfer::NAME => 'Warehouse',
                ],
                [
                    PushNotificationProviderTransfer::NAME => $pushNotificationProviderTransfer->getNameOrFail(),
                    PushNotificationProviderTransfer::ID_PUSH_NOTIFICATION_PROVIDER => $pushNotificationProviderTransfer->getIdPushNotificationProvider(),
                ],
            ),
            $this->createPushNotificationSubscriptionTransfer(
                [],
                [
                    PushNotificationGroupTransfer::IDENTIFIER => 2,
                    PushNotificationGroupTransfer::NAME => 'Warehouse',
                ],
                [
                    PushNotificationProviderTransfer::NAME => $pushNotificationProviderTransfer->getNameOrFail(),
                    PushNotificationProviderTransfer::ID_PUSH_NOTIFICATION_PROVIDER => $pushNotificationProviderTransfer->getIdPushNotificationProvider(),
                ],
            ),
        ];
    }

    /**
     * @return array<\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    public function createInvalidPushNotificationSubscriptions(): array
    {
        return [
            $this->createPushNotificationSubscriptionTransfer(
                [],
                [
                    PushNotificationGroupTransfer::IDENTIFIER => 1,
                    PushNotificationGroupTransfer::NAME => 'Warehouse',
                ],
                [
                    PushNotificationProviderTransfer::NAME => '',
                ],
            ),
            $this->createPushNotificationSubscriptionTransfer(
                [],
                [
                    PushNotificationGroupTransfer::IDENTIFIER => 1,
                    PushNotificationGroupTransfer::NAME => 'Warehouse',
                ],
                [
                    PushNotificationProviderTransfer::NAME => null,
                ],
            ),
        ];
    }

    /**
     * @return array<\Generated\Shared\Transfer\PushNotificationTransfer>
     */
    public function createValidPushNotifications(): array
    {
        $pushNotificationProviderTransfer = $this->havePushNotificationProvider(
            [
                PushNotificationProviderTransfer::NAME => 'web-push-php',
            ],
        );

        return [
            $this->createPushNotificationTransfer(
                [],
                [
                    PushNotificationGroupTransfer::IDENTIFIER => 1,
                    PushNotificationGroupTransfer::NAME => 'Warehouse',
                ],
                [
                    PushNotificationProviderTransfer::NAME => $pushNotificationProviderTransfer->getNameOrFail(),
                    PushNotificationProviderTransfer::ID_PUSH_NOTIFICATION_PROVIDER => $pushNotificationProviderTransfer->getIdPushNotificationProvider(),
                ],
            ),
            $this->createPushNotificationTransfer(
                [],
                [
                    PushNotificationGroupTransfer::IDENTIFIER => 1,
                    PushNotificationGroupTransfer::NAME => 'Warehouse',
                ],
                [
                    PushNotificationProviderTransfer::NAME => $pushNotificationProviderTransfer->getNameOrFail(),
                    PushNotificationProviderTransfer::ID_PUSH_NOTIFICATION_PROVIDER => $pushNotificationProviderTransfer->getIdPushNotificationProvider(),
                ],
            ),
        ];
    }

    /**
     * @return array<\Generated\Shared\Transfer\PushNotificationTransfer>
     */
    public function createInvalidPushNotifications(): array
    {
        return [
            $this->createPushNotificationTransfer(
                [],
                [
                    PushNotificationGroupTransfer::IDENTIFIER => 1,
                    PushNotificationGroupTransfer::NAME => 'Warehouse',
                ],
                [
                    PushNotificationProviderTransfer::NAME => '',
                ],
            ),
            $this->createPushNotificationTransfer(
                [],
                [
                    PushNotificationGroupTransfer::IDENTIFIER => 1,
                    PushNotificationGroupTransfer::NAME => 'Warehouse',
                ],
                [
                    PushNotificationProviderTransfer::NAME => null,
                ],
            ),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationTransfer $pushNotificationTransfer
     * @param \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
     *
     * @return \Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface
     */
    public function createPushNotificationSenderPluginMockWithExtendedPushNotificationTransfer(
        PushNotificationTransfer $pushNotificationTransfer,
        PushNotificationSubscriptionTransfer $pushNotificationSubscriptionTransfer
    ): PushNotificationSenderPluginInterface {
        return $this->createPushNotificationSenderPluginMock(
            [
                'send' => function () use ($pushNotificationTransfer, $pushNotificationSubscriptionTransfer): PushNotificationCollectionResponseTransfer {
                    $deliveryLogTransfer = (new PushNotificationSubscriptionDeliveryLogTransfer())
                        ->setPushNotification($pushNotificationTransfer)
                        ->setPushNotificationSubscription($pushNotificationSubscriptionTransfer);
                    $pushNotificationSubscriptionTransfer = $pushNotificationSubscriptionTransfer->addDeliveryLog(
                        $deliveryLogTransfer,
                    );
                    $pushNotificationTransfer = $pushNotificationTransfer->addSubscription($pushNotificationSubscriptionTransfer);

                    return (new PushNotificationCollectionResponseTransfer())
                        ->setPushNotifications(new ArrayObject([$pushNotificationTransfer]))
                        ->setErrors(new ArrayObject([]));
                },
            ],
        );
    }

    /**
     * @param array<string, callable> $params
     *
     * @return \Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface
     */
    public function createPushNotificationSenderPluginMock(array $params): PushNotificationSenderPluginInterface
    {
        $mock = Stub::makeEmpty(
            PushNotificationSenderPluginInterface::class,
            $params,
        );
        $mock->expects(new InvokedCountMatcher(1))->method('send');

        return $mock;
    }

    /**
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function createExpiredPushNotificationSubscription(): PushNotificationSubscriptionTransfer
    {
        return $this->havePushNotificationSubscription(
            [
                PushNotificationSubscriptionTransfer::EXPIRED_AT => (new DateTime())
                    ->sub(new DateInterval('P1D'))
                    ->getTimestamp(),
            ],
        );
    }

    /**
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function createActualPushNotificationSubscription(): PushNotificationSubscriptionTransfer
    {
        return $this->havePushNotificationSubscription(
            [
                PushNotificationSubscriptionTransfer::EXPIRED_AT => (new DateTime())
                    ->add(new DateInterval('P1D'))
                    ->getTimestamp(),
            ],
        );
    }

    /**
     * @param int $idPushNotificationSubscription
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscription|null
     */
    public function findPushNotificationSubscriptionEntityById(int $idPushNotificationSubscription): ?SpyPushNotificationSubscription
    {
        return SpyPushNotificationSubscriptionQuery::create()
            ->filterByIdPushNotificationSubscription($idPushNotificationSubscription)
            ->findOne();
    }

    /**
     * @return array<int, \Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    public function createValidPushNotificationProviders(): array
    {
        return [
            (new PushNotificationProviderTransfer())
                ->setName(static::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_ONE_SIGNAL),
        ];
    }

    /**
     * @return array<int, \Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    public function createInvalidPushNotificationProviders(): array
    {
        $this->havePushNotificationProvider(
            [
                PushNotificationProviderTransfer::NAME => static::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_WWW_GOOGLE_FIREBASE,
            ],
        );

        return [
            (new PushNotificationProviderTransfer())
                ->setName(static::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_WWW_GOOGLE_FIREBASE),
            (new PushNotificationProviderTransfer())
                ->setName(static::TEST_PUSH_NOTIFICATION_PROVIDER_NAME_WWW_GOOGLE_FIREBASE),
        ];
    }

    /**
     * @param bool $isAscending
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer
     */
    public function createPushNotificationProviderCriteriaTransferWithSort(
        bool $isAscending
    ): PushNotificationProviderCriteriaTransfer {
        $pushNotificationProviderCriteriaTransfer = new PushNotificationProviderCriteriaTransfer();
        $pushNotificationProviderCriteriaTransfer->addSort(
            (new SortTransfer())
                ->setField(SpyPushNotificationProviderTableMap::COL_NAME)
            ->setIsAscending($isAscending),
        );

        return $pushNotificationProviderCriteriaTransfer;
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @param int|null $page
     * @param int|null $maxPerPage
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer
     */
    public function createPushNotificationProviderCriteriaTransferWithPagination(
        ?int $limit = null,
        ?int $offset = null,
        ?int $page = null,
        ?int $maxPerPage = null
    ): PushNotificationProviderCriteriaTransfer {
        $pushNotificationProviderCriteriaTransfer = new PushNotificationProviderCriteriaTransfer();
        $paginationTransfer = new PaginationTransfer();
        if ($limit) {
            $paginationTransfer->setLimit($limit);
        }
        if ($offset) {
            $paginationTransfer->setOffset($offset);
        }
        if ($page) {
            $paginationTransfer->setPage($page);
        }
        if ($maxPerPage) {
            $paginationTransfer->setMaxPerPage($maxPerPage);
        }

        return $pushNotificationProviderCriteriaTransfer->setPagination($paginationTransfer);
    }

    /**
     * @param array<string, mixed> $pushNotificationSubscriptionOverride
     * @param array<string, mixed> $pushNotificationGroupOverride
     * @param array<string, mixed> $pushNotificationProviderOverride
     *
     * @return \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer
     */
    public function createPushNotificationSubscriptionTransfer(
        array $pushNotificationSubscriptionOverride,
        array $pushNotificationGroupOverride,
        array $pushNotificationProviderOverride
    ): PushNotificationSubscriptionTransfer {
        return (new PushNotificationSubscriptionBuilder($pushNotificationSubscriptionOverride))
            ->withGroup($pushNotificationGroupOverride)
            ->withProvider($pushNotificationProviderOverride)
            ->build();
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string $message
     *
     * @return void
     */
    public function assertErrorCollectionContainsFailedValidationRuleError(
        ArrayObject $errorTransfers,
        string $message
    ): void {
        $errorFound = false;
        foreach ($errorTransfers as $errorTransfer) {
            if (strstr($errorTransfer->getMessage(), $message) !== false) {
                $errorFound = true;
            }
        }

        $this->assertTrue(
            $errorFound,
            sprintf('Expected to have a message "%s" in the error collection but was not found', $message),
        );
    }

    /**
     * @param int $idPushNotification
     * @param int $idPushNotificationSubscription
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationSubscriptionDeliveryLog|null
     */
    public function findPushNotificationSubscriptionDeliveryLogEntity(
        int $idPushNotification,
        int $idPushNotificationSubscription
    ): ?SpyPushNotificationSubscriptionDeliveryLog {
        return SpyPushNotificationSubscriptionDeliveryLogQuery::create()
            ->filterByFkPushNotification($idPushNotification)
            ->filterByFkPushNotificationSubscription($idPushNotificationSubscription)
            ->findOne();
    }

    /**
     * @param array<string, mixed> $pushNotificationOverride
     * @param array<string, mixed> $pushNotificationGroupOverride
     * @param array<string, mixed> $pushNotificationProviderOverride
     *
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    protected function createPushNotificationTransfer(
        array $pushNotificationOverride,
        array $pushNotificationGroupOverride,
        array $pushNotificationProviderOverride
    ): PushNotificationTransfer {
        $pushNotificationTransfer = (new PushNotificationBuilder($pushNotificationOverride))->build();
        $pushNotificationGroupTransfer = (new PushNotificationGroupBuilder($pushNotificationGroupOverride))->build();
        $pushNotificationProviderTransfer = (new PushNotificationProviderBuilder($pushNotificationProviderOverride))->build();

        return $pushNotificationTransfer
            ->setGroup($pushNotificationGroupTransfer)
            ->setProvider($pushNotificationProviderTransfer);
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery
     */
    protected function getPushNotificationQueryQuery(): SpyPushNotificationQuery
    {
        return SpyPushNotificationQuery::create();
    }
}
