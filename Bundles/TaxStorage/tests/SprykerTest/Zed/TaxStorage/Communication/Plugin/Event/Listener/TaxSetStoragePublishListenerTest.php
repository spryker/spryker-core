<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Tax\Dependency\TaxEvents;
use Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener\TaxSetStoragePublishListener;
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
 * @group TaxSetStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class TaxSetStoragePublishListenerTest extends Unit
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
     * @var \Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener\TaxSetStoragePublishListener
     */
    protected $taxSetStoragePublishListener;

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
        $this->taxSetStoragePublishListener = new TaxSetStoragePublishListener();
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
            (new EventEntityTransfer())->setId($idTaxSet),
        ];

        // Act
        $this->taxSetStoragePublishListener->handleBulk(
            $eventTransfers,
            TaxEvents::ENTITY_SPY_TAX_SET_CREATE
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
