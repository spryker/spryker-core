<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinuedStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductDiscontinued\Persistence\Map\SpyProductDiscontinuedNoteTableMap;
use Spryker\Zed\ProductDiscontinued\Dependency\ProductDiscontinuedEvents;
use Spryker\Zed\ProductDiscontinuedStorage\Communication\Plugin\Event\Listener\ProductDiscontinuedNoteStorageListener;
use Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductDiscontinuedStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductDiscontinuedNoteStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductDiscontinuedNoteStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepository
     */
    protected $productDiscontinuedStorageRepository;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Communication\Plugin\Event\Listener\ProductDiscontinuedNoteStorageListener
     */
    protected $productDiscontinuedNoteStorageListener;

    /**
     * @var \Generated\Shared\Transfer\ProductDiscontinuedTransfer
     */
    protected $productDiscontinuedTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->productDiscontinuedStorageRepository = new ProductDiscontinuedStorageRepository();

        $this->productDiscontinuedNoteStorageListener = new ProductDiscontinuedNoteStorageListener();
        $this->productDiscontinuedNoteStorageListener->setFacade($this->tester->getMockedFacade());

        $this->productDiscontinuedTransfer = $this->tester->createProductDiscontinued();
    }

    /**
     * @return void
     */
    public function testHandleBulkProductDiscontinuedStorageEntityCanBePublished(): void
    {
        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductDiscontinuedNoteTableMap::COL_FK_PRODUCT_DISCONTINUED => $this->productDiscontinuedTransfer->getIdProductDiscontinued(),
            ]),
        ];

        // Act
        $this->productDiscontinuedNoteStorageListener->handleBulk(
            $eventTransfers,
            ProductDiscontinuedEvents::ENTITY_SPY_PRODUCT_DISCONTINUED_NOTE_CREATE
        );
        $productDiscontinuedEntityTransfers = $this->productDiscontinuedStorageRepository
            ->findProductDiscontinuedStorageEntitiesByIds(
                [$this->productDiscontinuedTransfer->getIdProductDiscontinued()]
            );

        // Assert
        $this->assertCount(count($this->tester->getLocaleFacade()->getAvailableLocales()), $productDiscontinuedEntityTransfers);
    }
}
