<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreStorage\Communication\Plugin\Publisher\CountryStore;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Country\Persistence\Map\SpyCountryStoreTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Shared\StoreStorage\StoreStorageConfig;
use Spryker\Zed\StoreStorage\Communication\Plugin\Publisher\CountryStore\CountryStoreWritePublisherPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group CountryStore
 * @group CountryStoreStoragePublisherTest
 * Add your own group annotations below this line
 */
class CountryStoreStoragePublisherTest extends Unit
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
     * @var int
     */
    protected const STORE_ID = 1;

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
    public function testStoreCountryWritePublisherStoreData(): void
    {
        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())
                ->setForeignKeys([
                    SpyCountryStoreTableMap::COL_FK_STORE => static::STORE_ID,
                ]),
        ];

        // Act
        (new CountryStoreWritePublisherPlugin())->handleBulk($eventTransfers, StoreStorageConfig::ENTITY_SPY_COUNTRY_STORE_CREATE);

        // Assert
        $storeStorageEntity = $this->tester->findStoreStorageEntityByIdStore(1);
        $this->assertNotNull($storeStorageEntity);
        $this->assertArrayHasKey(static::DATA_KEY_ID_STORE, $storeStorageEntity->getData());
        $this->assertArrayHasKey(static::DATA_KEY_STORE_NAME, $storeStorageEntity->getData());
        $this->assertSame(static::STORE_ID, $storeStorageEntity->getData()[static::DATA_KEY_ID_STORE]);
        $this->assertSame(static::STORE_NAME, $storeStorageEntity->getData()[static::DATA_KEY_STORE_NAME]);
    }
}
