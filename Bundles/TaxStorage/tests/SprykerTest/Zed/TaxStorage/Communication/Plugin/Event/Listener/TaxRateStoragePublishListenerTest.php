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
use Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener\TaxRateStoragePublishListener;
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
 * @group TaxRateStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class TaxRateStoragePublishListenerTest extends Unit
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
     * @var \Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener\TaxRateStoragePublishListener
     */
    protected $taxRateStoragePublishListener;

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
        $this->taxRateStoragePublishListener = new TaxRateStoragePublishListener();
        $this->taxSetTransfer = $this->tester->haveTaxSetWithTaxRates();
    }

    /**
     * @return void
     */
    public function testHandleBulkTaxSetStorageEntityCanBePublished(): void
    {
        $idTaxRate = $this->taxSetTransfer->getTaxRates()[0]->getIdTaxRate();

        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setId($idTaxRate),
        ];

        // Act
        $this->taxRateStoragePublishListener->handleBulk(
            $eventTransfers,
            TaxEvents::TAX_SET_PUBLISH
        );
        $synchronizationDataTransfers = $this->taxStorageRepository
            ->getSynchronizationDataTransfersFromTaxSetStoragesByIdTaxSets(
                [$idTaxRate]
            );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
        $synchronizationDataTransfersDataArray = json_decode($synchronizationDataTransfers[0]->getData(), true);
        $this->assertEquals($this->taxSetTransfer->getIdTaxSet(), $synchronizationDataTransfersDataArray['fk_tax_set']);
    }
}
