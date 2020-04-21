<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelStorage\Business;

use Codeception\Test\Unit;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductLabelStorage
 * @group Business
 * @group Facade
 * @group ProductLabelStorageFacadeTest
 * Add your own group annotations below this line
 */
class ProductLabelStorageFacadeTest extends Unit
{
    protected const ID_PRODUCT_LABEL_DICTIONARY_STORAGE = 1;

    /**
     * @var \SprykerTest\Zed\ProductLabelStorage\ProductLabelStorageBusinessTester
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
    public function testWriteProductLabelDictionaryStorageCollection(): void
    {
        //Arrange
        $this->cleanupProductLabelDictionaryStorage(static::ID_PRODUCT_LABEL_DICTIONARY_STORAGE);
        $productLabelDictionaryStorageBeforeCount = $this->createProductLabelDictionaryStorageQuery()->count();

        //Act
        $this->tester->getFacade()->writeProductLabelDictionaryStorageCollection();

        //Assert
        $productLabelDictionaryStorageAfterCount = $this->createProductLabelDictionaryStorageQuery()->count();
        $this->tester->assertGreaterThan(
            $productLabelDictionaryStorageAfterCount,
            $productLabelDictionaryStorageBeforeCount,
            'Product Label Dictionary Storage record count does is less then expected value.'
        );
    }

    /**
     * @param int $idProductLabelDictionaryStorage
     *
     * @return void
     */
    protected function cleanupProductLabelDictionaryStorage(int $idProductLabelDictionaryStorage): void
    {
        $this->createProductLabelDictionaryStorageQuery()
            ->filterByIdProductLabelDictionaryStorage($idProductLabelDictionaryStorage)
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery
     */
    protected function createProductLabelDictionaryStorageQuery(): SpyProductLabelDictionaryStorageQuery
    {
        return SpyProductLabelDictionaryStorageQuery::create();
    }
}
