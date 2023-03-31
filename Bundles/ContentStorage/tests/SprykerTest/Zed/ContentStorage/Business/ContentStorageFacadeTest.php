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
use Generated\Shared\Transfer\StoreTransfer;
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
                $data[ContentTransfer::LOCALIZED_CONTENTS][0][LocalizedContentTransfer::PARAMETERS],
            ),
        ]);

        $contentStoreExpectedData = [
            [
                static::CONTENT_STORAGE_DATA_KEY => $contentData,
                static::CONTENT_STORAGE_LOCALE_KEY => static::DE_LOCALE,
            ],
            [
                static::CONTENT_STORAGE_DATA_KEY => $contentData,
                static::CONTENT_STORAGE_LOCALE_KEY => static::EN_LOCALE,
            ],
            [
                static::CONTENT_STORAGE_DATA_KEY => $contentData,
                static::CONTENT_STORAGE_LOCALE_KEY => static::FR_LOCALE,
            ],
            [
                static::CONTENT_STORAGE_DATA_KEY => $contentData,
                static::CONTENT_STORAGE_LOCALE_KEY => static::PL_LOCALE,
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
            ->select([static::CONTENT_STORAGE_DATA_KEY, static::CONTENT_STORAGE_LOCALE_KEY])
            ->limit(static::EXPECTED_STORED_CONTENT_ITEMS)
            ->find()
            ->toArray();

        return $contentStorageDataList;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToStoreFacadeBridge
     */
    protected function createContentStorageToStoreFacadeBridgeMock(): ContentStorageToStoreFacadeBridge
    {
        $contentStorageToStoreFacadeBridgeMock = $this->createPartialMock(ContentStorageToStoreFacadeBridge::class, ['getAllStores']);

        $storeBuilder = new StoreBuilder([
            StoreTransfer::AVAILABLE_LOCALE_ISO_CODES => [
                static::DE_LOCALE_KEY => static::DE_LOCALE,
                static::EN_LOCALE_NAME => static::EN_LOCALE,
                static::FR_LOCALE_NAME => static::FR_LOCALE,
            ],
        ]);
        $store = $storeBuilder->build();

        $storeWithSharedPersistanceBuilder = new StoreBuilder([
            StoreTransfer::AVAILABLE_LOCALE_ISO_CODES => [
                static::DE_LOCALE_KEY => static::DE_LOCALE,
                static::EN_LOCALE_NAME => static::EN_LOCALE,
                static::PL_LOCALE_NAME => static::PL_LOCALE,
            ],
        ]);
        $storeWithSharedPersistance = $storeWithSharedPersistanceBuilder->build();
        $contentStorageToStoreFacadeBridgeMock->method('getAllStores')->willReturn([$store, $storeWithSharedPersistance]);

        return $contentStorageToStoreFacadeBridgeMock;
    }
}
