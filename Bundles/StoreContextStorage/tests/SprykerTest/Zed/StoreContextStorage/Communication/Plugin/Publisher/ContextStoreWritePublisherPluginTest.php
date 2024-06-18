<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreContextStorage\Communication\Plugin\Publisher;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\StoreContext\Persistence\Map\SpyStoreContextTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Client\Store\StoreDependencyProvider;
use Spryker\Shared\StoreContextStorage\StoreContextStorageConfig;
use Spryker\Zed\StoreContextStorage\Communication\Plugin\Publisher\ContextStoreWritePublisherPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreContextStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group ContextStoreWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class ContextStoreWritePublisherPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const DATA_CONTEXT_COLLECTION = 'application_context_collection';

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
     * @var \SprykerTest\Zed\StoreContextStorage\StoreContextStorageCommunicationTester
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
    public function testContextStoreWritePublisherStoresData(): void
    {
        // Arrange
        $this->tester->setDependency(StoreDependencyProvider::SERVICE_STORE, static::STORE_NAME);

        $eventTransfers = [
            (new EventEntityTransfer())
                ->setForeignKeys([
                    SpyStoreContextTableMap::COL_FK_STORE => static::STORE_ID,
                ]),
        ];

        // Act
        (new ContextStoreWritePublisherPlugin())->handleBulk($eventTransfers, StoreContextStorageConfig::ENTITY_SPY_STORE_CONTEXT_CREATE);

        // Assert
        $storeStorageData = $this->tester->findStoreStorageEntityByIdStore(1)->getData();
        $this->assertNotNull($storeStorageData);
        $this->assertArrayHasKey(static::DATA_KEY_ID_STORE, $storeStorageData);
        $this->assertArrayHasKey(static::DATA_CONTEXT_COLLECTION, $storeStorageData);
        $this->assertSame(static::STORE_ID, $storeStorageData[static::DATA_KEY_ID_STORE]);
    }
}
