<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\LocalizedContentTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Shared\ContentStorage\ContentStorageConfig;
use Spryker\Zed\ContentStorage\ContentStorageDependencyProvider;
use Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToStoreFacadeBridge;
use Spryker\Zed\ContentStorage\Persistence\ContentStoragePersistenceFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ContentStorage
 * @group Business
 * @group Facade
 * @group ContentStorageFacadeTest
 * Add your own group annotations below this line
 */
class ContentStorageFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const CONTENT_STORAGE_DATA_KEY = 'data';

    /**
     * @var string
     */
    protected const CONTENT_STORAGE_LOCALE_KEY = 'locale';

    /**
     * @var string
     */
    protected const EN_LOCALE = 'en_US';

    /**
     * @var string
     */
    protected const FR_LOCALE = 'fr_FR';

    /**
     * @var string
     */
    protected const DE_LOCALE = 'de_DE';

    /**
     * @var string
     */
    protected const PL_LOCALE = 'pl_PL';

    /**
     * @var string
     */
    protected const DE_LOCALE_KEY = 'de';

    /**
     * @var string
     */
    protected const EN_LOCALE_NAME = 'en';

    /**
     * @var string
     */
    protected const FR_LOCALE_NAME = 'fr';

    /**
     * @var string
     */
    protected const PL_LOCALE_NAME = 'pl';

    /**
     * @var int
     */
    protected const EXPECTED_STORED_CONTENT_ITEMS = 4;

    /**
     * @var \SprykerTest\Zed\ContentStorage\ContentStorageBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->setDependency(ContentStorageDependencyProvider::FACADE_STORE, $this->createContentStorageToStoreFacadeBridgeMock());
    }

    /**
     * @return void
     */
    public function testPublishStoresEntityData(): void
    {
        // Arrange
        $data = [
            ContentTransfer::CONTENT_TERM_KEY => 'test-term',
            ContentTransfer::LOCALIZED_CONTENTS => [
                [
                    LocalizedContentTransfer::PARAMETERS => '["M23222", "M23232"]',
                ],
            ],
        ];
        $contentTransfer = $this->tester->haveContent($data);

        // Act
        $this->tester->getFacade()->publish([$contentTransfer->getIdContent()]);

        // Assert
        $contentData = json_encode([
            ContentTransfer::ID_CONTENT => $contentTransfer->getIdContent(),
            ContentStorageConfig::TERM_KEY => $data[ContentTransfer::CONTENT_TERM_KEY],
            ContentStorageConfig::CONTENT_KEY => json_decode(
                $data[ContentTransfer::LOCALIZED_CONTENTS][0][LocalizedContentTransfer::PARAMETERS]
            ),
        ]);

        $contentStoreExpectedData = [
            [
                self::CONTENT_STORAGE_DATA_KEY => $contentData,
                self::CONTENT_STORAGE_LOCALE_KEY => self::DE_LOCALE,
            ],
            [
                self::CONTENT_STORAGE_DATA_KEY => $contentData,
                self::CONTENT_STORAGE_LOCALE_KEY => self::EN_LOCALE,
            ],
            [
                self::CONTENT_STORAGE_DATA_KEY => $contentData,
                self::CONTENT_STORAGE_LOCALE_KEY => self::FR_LOCALE,
            ],
            [
                self::CONTENT_STORAGE_DATA_KEY => $contentData,
                self::CONTENT_STORAGE_LOCALE_KEY => self::PL_LOCALE,
            ],
        ];

        $contentStorageDataList = $this->getContentStorageDataList();

        $this->assertEqualsCanonicalizing($contentStoreExpectedData, $contentStorageDataList);
    }

    /**
     * @return array
     */
    protected function getContentStorageDataList(): array
    {
        $persistenceFactory = new ContentStoragePersistenceFactory();
        $contentStorageDataList = $persistenceFactory->createContentStorageQuery()
            ->lastCreatedFirst()
            ->select([self::CONTENT_STORAGE_DATA_KEY, self::CONTENT_STORAGE_LOCALE_KEY])
            ->limit(self::EXPECTED_STORED_CONTENT_ITEMS)
            ->find()
            ->toArray();

        return $contentStorageDataList;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToStoreFacadeBridge
     */
    protected function createContentStorageToStoreFacadeBridgeMock(): ContentStorageToStoreFacadeBridge
    {
        $contentStorageToStoreFacadeBridgeMock = $this->createPartialMock(ContentStorageToStoreFacadeBridge::class, ['getCurrentStore', 'getStoresWithSharedPersistence']);

        $storeBuilder = new StoreBuilder([
            'availableLocaleIsoCodes' => [
                self::DE_LOCALE_KEY => self::DE_LOCALE,
                self::EN_LOCALE_NAME => self::EN_LOCALE,
                self::FR_LOCALE_NAME => self::FR_LOCALE,
            ],
        ]);
        $store = $storeBuilder->build();
        $contentStorageToStoreFacadeBridgeMock->method('getCurrentStore')->willReturn($store);

        $storeWithSharedPersistanceBuilder = new StoreBuilder([
            'availableLocaleIsoCodes' => [
                self::DE_LOCALE_KEY => self::DE_LOCALE,
                self::EN_LOCALE_NAME => self::EN_LOCALE,
                self::PL_LOCALE_NAME => self::PL_LOCALE,
            ],
        ]);
        $storeWithSharedPersistance = $storeWithSharedPersistanceBuilder->build();
        $contentStorageToStoreFacadeBridgeMock->method('getStoresWithSharedPersistence')->willReturn([$storeWithSharedPersistance]);

        return $contentStorageToStoreFacadeBridgeMock;
    }
}
