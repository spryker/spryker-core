<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTaxTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Tax\Dependency\TaxEvents;
use Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener\TaxSetTaxStoragePublishListener;
use Spryker\Zed\TaxStorage\Persistence\TaxStorageRepository;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group TaxStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group TaxSetTaxStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class TaxSetTaxStoragePublishListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\TaxStorage\TaxStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface
     */
    protected $taxStorageRepository;

    /**
     * @var \Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener\TaxSetTaxStoragePublishListener
     */
    protected $taxSetTaxStoragePublishListener;

    /**
     * @var \Generated\Shared\Transfer\TaxSetTransfer
     */
    protected $taxSetTransfer;

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

        $this->taxStorageRepository = new TaxStorageRepository();
        $this->taxSetTaxStoragePublishListener = new TaxSetTaxStoragePublishListener();
        $this->taxSetTransfer = $this->tester->haveTaxSetWithTaxRates();
    }

    /**
     * @return void
     */
    public function testHandleBulkTaxSetStorageEntityCanBePublished(): void
    {
        $idTaxSet = $this->taxSetTransfer->getIdTaxSet();

        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([SpyTaxSetTaxTableMap::COL_FK_TAX_SET => $idTaxSet]),
        ];

        // Act
        $this->taxSetTaxStoragePublishListener->handleBulk(
            $eventTransfers,
            TaxEvents::ENTITY_SPY_TAX_SET_TAX_CREATE
        );
        $synchronizationDataTransfers = $this->taxStorageRepository
            ->getSynchronizationDataTransfersFromTaxSetStoragesByIdTaxSets(
                [$idTaxSet]
            );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
        /** @var array $synchronizationDataTransfersDataArray */
        $synchronizationDataTransfersDataArray = $synchronizationDataTransfers[0]->getData();
        $this->assertEquals($idTaxSet, $synchronizationDataTransfersDataArray['id_tax_set']);
    }
}
