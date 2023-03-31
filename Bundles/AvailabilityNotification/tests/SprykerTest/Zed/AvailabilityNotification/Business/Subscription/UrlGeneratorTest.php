<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Business\Subscription;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig;
use Spryker\Zed\AvailabilityNotification\Business\Resolver\BaseUrlGetStrategyResolver;
use Spryker\Zed\AvailabilityNotification\Business\Resolver\BaseUrlGetStrategyResolverInterface;
use Spryker\Zed\AvailabilityNotification\Business\Strategy\StoreYvesBaseUrlGetStrategy;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGenerator;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use SprykerTest\Zed\AvailabilityNotification\AvailabilityNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityNotification
 * @group Business
 * @group Subscription
 * @group UrlGeneratorTest
 * Add your own group annotations below this line
 */
class UrlGeneratorTest extends Unit
{
    /**
     * @uses \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig::AVAILABILITY_NOTIFICATION_UNSUBSCRIBE_BY_KEY_URI
     *
     * @var string
     */
    protected const AVAILABILITY_NOTIFICATION_UNSUBSCRIBE_BY_KEY_URI = '/%s/availability-notification/unsubscribe-by-key/%s';

    /**
     * @uses \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGenerator::PORT_HTTPS
     *
     * @var int
     */
    protected const PORT_HTTPS = 443;

    /**
     * @var string
     */
    protected const BASE_URL_YVES = 'https://yves.spryker.local';

    /**
     * @var string
     */
    protected const HOST_YVES_DE = 'yves.de.spryker.local';

    /**
     * @var string
     */
    protected const TEST_LOCALIZED_URL = '/de/test-product';

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var string
     */
    protected const STORE_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_AT = 'AT';

    /**
     * @var string
     */
    protected const SUBSCRIPTION_KEY = 'test';

    /**
     * @var \SprykerTest\Zed\AvailabilityNotification\AvailabilityNotificationBusinessTester
     */
    protected AvailabilityNotificationBusinessTester $tester;

    /**
     * @dataProvider getUrlGeneratorDataProvider
     *
     * @param array<string, string>|string $storeToYvesHostMapping
     * @param string $expectedBaseUrl
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return void
     */
    public function testCreateUnsubscriptionLink(
        array $storeToYvesHostMapping,
        string $expectedBaseUrl,
        ?StoreTransfer $storeTransfer = null
    ): void {
        // Arrange
        $availabilityNotificationConfigMock = $this->createAvailabilityNotificationConfigMock($storeToYvesHostMapping, static::BASE_URL_YVES);
        $availabilityNotificationSubscriptionTransfer = (new AvailabilityNotificationSubscriptionTransfer())
            ->setSubscriptionKey(static::SUBSCRIPTION_KEY)
            ->setLocale((new LocaleTransfer())->setLocaleName(static::LOCALE_DE))
            ->setStore($storeTransfer);

        $expectedUnsubscriptionUrl = sprintf(
            '%s/de/availability-notification/unsubscribe-by-key/%s',
            $expectedBaseUrl,
            static::SUBSCRIPTION_KEY,
        );

        // Act
        $unsubscriptionLink = $this->createUrlGenerator($availabilityNotificationConfigMock)
            ->createUnsubscriptionLink($availabilityNotificationSubscriptionTransfer);

        // Assert
        $this->assertSame($expectedUnsubscriptionUrl, $unsubscriptionLink);
    }

    /**
     * @dataProvider getUrlGeneratorDataProvider
     *
     * @param array<string, string>|string $storeToYvesHostMapping
     * @param string $expectedBaseUrl
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return void
     */
    public function testGenerateProductUrl(
        array $storeToYvesHostMapping,
        string $expectedBaseUrl,
        ?StoreTransfer $storeTransfer = null
    ): void {
        // Arrange
        $availabilityNotificationConfigMock = $this->createAvailabilityNotificationConfigMock($storeToYvesHostMapping);
        $localizedUrlTransfer = (new LocalizedUrlTransfer())->setUrl(static::TEST_LOCALIZED_URL);

        $expectedUnsubscriptionUrl = sprintf(
            '%s%s',
            $expectedBaseUrl,
            static::TEST_LOCALIZED_URL,
        );

        // Act
        $unsubscriptionLink = $this->createUrlGenerator($availabilityNotificationConfigMock)
            ->generateProductUrl($localizedUrlTransfer, $storeTransfer);

        // Assert
        $this->assertSame($expectedUnsubscriptionUrl, $unsubscriptionLink);
    }

    /**
     * @return array<string, array<string|array<string, string>|\Generated\Shared\Transfer\StoreTransfer|null>>
     */
    public function getUrlGeneratorDataProvider(): array
    {
        return [
            'Should use current store URL when store is not provided.' => [
                [], static::BASE_URL_YVES,
            ],
            'Should use current store URL when store name is not provided.' => [
                [], static::BASE_URL_YVES, new StoreTransfer(),
            ],
            'Should use current store URL when store to host mapping is not set.' => [
                [], static::BASE_URL_YVES, (new StoreTransfer())->setName(static::STORE_DE),
            ],
            'Should use current store URL when store is not in host mapping.' => [
                [
                    static::STORE_DE => static::HOST_YVES_DE,
                ],
                static::BASE_URL_YVES,
                (new StoreTransfer())->setName(static::STORE_AT),
            ],
            'Should use store specific URL when store is provided.' => [
                [
                    static::STORE_DE => static::HOST_YVES_DE,
                ],
                sprintf('https://%s', static::HOST_YVES_DE),
                (new StoreTransfer())->setName(static::STORE_DE),
            ],
        ];
    }

    /**
     * @param array<string, string> $storeToYvesHostMapping
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig
     */
    protected function createAvailabilityNotificationConfigMock(array $storeToYvesHostMapping): AvailabilityNotificationConfig
    {
        $availabilityNotificationConfigMock = $this->createMock(AvailabilityNotificationConfig::class);

        $availabilityNotificationConfigMock->method('getUnsubscribeUri')
            ->willReturn(static::AVAILABILITY_NOTIFICATION_UNSUBSCRIBE_BY_KEY_URI);

        $availabilityNotificationConfigMock->method('getStoreToYvesHostMapping')
            ->willReturn($storeToYvesHostMapping);

        $availabilityNotificationConfigMock->method('getBaseUrlYves')
            ->willReturn(static::BASE_URL_YVES);

        $availabilityNotificationConfigMock->method('getBaseUrlYvesPort')
            ->willReturn(static::PORT_HTTPS);

        return $availabilityNotificationConfigMock;
    }

    /**
     * @param \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig $availabilityNotificationConfig
     *
     * @return \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface
     */
    protected function createUrlGenerator(AvailabilityNotificationConfig $availabilityNotificationConfig): UrlGeneratorInterface
    {
        return new UrlGenerator(
            $availabilityNotificationConfig,
            $this->createBaseUrlGetStrategyResolver($availabilityNotificationConfig),
        );
    }

    /**
     * @param \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig $availabilityNotificationConfig
     *
     * @return \Spryker\Zed\AvailabilityNotification\Business\Resolver\BaseUrlGetStrategyResolverInterface
     */
    protected function createBaseUrlGetStrategyResolver(AvailabilityNotificationConfig $availabilityNotificationConfig): BaseUrlGetStrategyResolverInterface
    {
        return new BaseUrlGetStrategyResolver([
            new StoreYvesBaseUrlGetStrategy(
                $availabilityNotificationConfig,
                $this->createStoreFacadeMock(),
            ),
        ]);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    protected function createStoreFacadeMock(): AvailabilityNotificationToStoreFacadeInterface
    {
        return $this->createMock(AvailabilityNotificationToStoreFacadeInterface::class);
    }
}
