<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotificationWebPushPhp;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\PushNotificationBuilder;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use GuzzleHttp\Psr7\Request;
use Minishlink\WebPush\Encryption;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationProvider;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery;
use Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\MessageSentReport;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface;
use Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig;
use SprykerTest\Zed\PushNotificationWebPushPhp\Mock\FlushResponseMock;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class PushNotificationWebPushPhpBusinessTester extends Actor
{
    use _generated\PushNotificationWebPushPhpBusinessTesterActions;

    /**
     * @var string
     */
    public const PAYLOAD_KEY_ENDPOINT = 'endpoint';

    /**
     * @var string
     */
    public const TEST_ENDPOINT = 'https://foo.bar';

    /**
     * @var string
     */
    protected const NOT_APPLICABLE_PROVIDER_NAME = 'extra-provider';

    /**
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    public function createValidPushNotificationTransfer(): PushNotificationTransfer
    {
        $pushNotificationBuilder = new PushNotificationBuilder(
            [
                PushNotificationTransfer::PAYLOAD => ['foo' => 'bar'],
            ],
        );
        $pushNotificationTransfer = $pushNotificationBuilder->build();
        $pushNotificationTransfer->setProvider(
            (new PushNotificationProviderTransfer())
                ->setName(PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME),
        );

        return $pushNotificationTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    public function createInvalidPushNotificationTransfer(): PushNotificationTransfer
    {
        $pushNotificationBuilder = new PushNotificationBuilder(
            [
                PushNotificationTransfer::PAYLOAD => ['foo' => str_repeat('a', Encryption::MAX_PAYLOAD_LENGTH)],
            ],
        );
        $pushNotificationTransfer = $pushNotificationBuilder->build();
        $pushNotificationTransfer->setProvider(
            (new PushNotificationProviderTransfer())
                ->setName(PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME),
        );

        return $pushNotificationTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    public function createApplicablePushNotification(): PushNotificationTransfer
    {
        return $this->havePushNotification(
            [
                PushNotificationTransfer::PAYLOAD => [static::PAYLOAD_KEY_ENDPOINT => static::TEST_ENDPOINT],
            ],
            [
                PushNotificationProviderTransfer::NAME => PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME,
            ],
        );
    }

    /**
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    public function createNotApplicablePushNotification(): PushNotificationTransfer
    {
        return $this->havePushNotification(
            [
                PushNotificationTransfer::PAYLOAD => [],
            ],
            [
                PushNotificationProviderTransfer::NAME => static::NOT_APPLICABLE_PROVIDER_NAME,
            ],
        );
    }

    /**
     * @param string $name
     *
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationProvider|null
     */
    public function findPushNotificationProviderEntityByName(string $name): ?SpyPushNotificationProvider
    {
        return $this->createPushNotificationProviderQuery()
            ->filterByName($name)
            ->findOne();
    }

    /**
     * @param int $pushNotificationIdentifier
     * @param int $pushNotificationSubscriptionIdentifier
     *
     * @return void
     */
    public function mockWebPusherWithOneMessage(int $pushNotificationIdentifier, int $pushNotificationSubscriptionIdentifier): void
    {
        $this->mockFactoryMethod(
            'getWebPushNotificator',
            $this->createWebPushAdapterMock(
                new InvokedCount(1),
                [
                    (new MessageSentReport($pushNotificationIdentifier, $pushNotificationSubscriptionIdentifier, new Request('POST', '')))
                        ->setSuccess(true),
                ],
            ),
        );
    }

    /**
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $expectedInvocationCounter
     * @param array<int, \Minishlink\WebPush\MessageSentReport> $flushMessageSentReports
     *
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushInterface
     */
    public function createWebPushAdapterMock(
        InvokedCount $expectedInvocationCounter,
        array $flushMessageSentReports = []
    ): PushNotificationWebPushPhpToWebPushInterface {
        $webPushAdapterMock = Stub::makeEmpty(PushNotificationWebPushPhpToWebPushInterface::class);
        $webPushAdapterMock->expects($expectedInvocationCounter)->method('queueNotification');
        $webPushAdapterMock->method('flush')->willReturn(
            (new FlushResponseMock($flushMessageSentReports))->getResponse(),
        );

        return $webPushAdapterMock;
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationProviderQuery
     */
    public function createPushNotificationProviderQuery(): SpyPushNotificationProviderQuery
    {
        return SpyPushNotificationProviderQuery::create();
    }

    /**
     * @return \Orm\Zed\PushNotification\Persistence\SpyPushNotificationQuery
     */
    protected function createPushNotificationQuery(): SpyPushNotificationQuery
    {
        return SpyPushNotificationQuery::create();
    }
}
