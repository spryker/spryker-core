<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreStorage\Communication\Plugin\Publisher\CurrencyStore;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyStoreTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Client\Store\StoreDependencyProvider;
use Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface;
use Spryker\Shared\StoreStorage\StoreStorageConfig;
use Spryker\Zed\StoreStorage\Communication\Plugin\Publisher\CurrencyStore\CurrencyStoreWritePublisherPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group CurrencyStore
 * @group CurrencyStoreStoragePublisherTest
 * Add your own group annotations below this line
 */
class CurrencyStoreStoragePublisherTest extends Unit
{
    /**
     * @var string
     */
    protected const DATA_KEY_STORE_NAME = 'name';

    /**
     * @var string
     */
    protected const DATA_KEY_ID_STORE = 'id_store';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\StoreStorage\StoreStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    public function testStoreCurrencyWritePublisherStoreData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);

        $this->tester->setDependency(StoreDependencyProvider::STORE, $this->getStoreToStoreInterface());
        $this->tester->setDependency(StoreDependencyProvider::SERVICE_STORE, $storeTransfer);

        $eventTransfers = [
            (new EventEntityTransfer())
                ->setForeignKeys([
                    SpyCurrencyStoreTableMap::COL_FK_STORE => $storeTransfer->getIdStore(),
                ]),
        ];

        // Act
        (new CurrencyStoreWritePublisherPlugin())->handleBulk($eventTransfers, StoreStorageConfig::ENTITY_SPY_CURRENCY_STORE_CREATE);

        // Assert
        $storeStorageEntity = $this->tester->findStoreStorageEntityByIdStore($storeTransfer->getIdStore());
        $this->assertNotNull($storeStorageEntity);
        $this->assertArrayHasKey(static::DATA_KEY_ID_STORE, $storeStorageEntity->getData());
        $this->assertArrayHasKey(static::DATA_KEY_STORE_NAME, $storeStorageEntity->getData());
        $this->assertSame($storeTransfer->getIdStore(), $storeStorageEntity->getData()[static::DATA_KEY_ID_STORE]);
        $this->assertSame($storeTransfer->getName(), $storeStorageEntity->getData()[static::DATA_KEY_STORE_NAME]);
    }

    /**
     * @return \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface
     */
    protected function getStoreToStoreInterface(): StoreToStoreInterface
    {
        $storeToStoreInterfaceMock = $this->getMockBuilder(StoreToStoreInterface::class)->getMock();
        $storeToStoreInterfaceMock->method('getAvailableLocaleIsoCodesFor')->willReturn([$this->tester::LOCALE_DE]);

        return $storeToStoreInterfaceMock;
    }
}
