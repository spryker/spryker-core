<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\TaxRateStorageTransfer;
use Generated\Shared\Transfer\TaxSetStorageTransfer;
use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Tax\Dependency\TaxEvents;
use Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener\TaxSetStorageUnpublishListener;
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
 * @group TaxSetStorageUnpublishListenerTest
 * Add your own group annotations below this line
 */
class TaxSetStorageUnpublishListenerTest extends Unit
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
     * @var \Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener\TaxSetStorageUnpublishListener
     */
    protected $taxSetStorageUnpublishListener;

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
        $this->taxSetStorageUnpublishListener = new TaxSetStorageUnpublishListener();
        $this->taxSetTransfer = $this->tester->haveTaxSetWithTaxRates();
    }

    /**
     * @return void
     */
    public function testHandleBulkTaxSetStorageEntityCanBeUnpublished(): void
    {
        $idTaxSet = $this->taxSetTransfer->getIdTaxSet();

        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setId($idTaxSet),
        ];
        $this->saveTaxSetStorageEntity();

        // Act
        $this->taxSetStorageUnpublishListener->handleBulk(
            $eventTransfers,
            TaxEvents::ENTITY_SPY_TAX_SET_DELETE
        );
        $taxProductStorageEntities = $this->taxStorageRepository
            ->getSynchronizationDataTransfersFromTaxSetStoragesByIdTaxSets([
                $idTaxSet,
            ]);

        // Assert
        $this->assertCount(0, $taxProductStorageEntities);
    }

    /**
     * @return void
     */
    protected function saveTaxSetStorageEntity(): void
    {
        $taxRateTransfer = $this->taxSetTransfer->getTaxRates()[0];
        $taxRateStorageTransfer = new TaxRateStorageTransfer();
        $taxRateStorageTransfer->fromArray($taxRateTransfer->toArray(), true);
        $taxRateStorageTransfer->setCountry($taxRateTransfer->getCountry()->getIso2Code());

        $taxSetStorageTransfer = (new TaxSetStorageTransfer())
            ->fromArray($this->taxSetTransfer->toArray(), true)
            ->addTaxRate($taxRateStorageTransfer);

        $taxSetStorageEntity = (new SpyTaxSetStorage())
            ->setFkTaxSet($this->taxSetTransfer->getIdTaxSet())
            ->setData($taxSetStorageTransfer->toArray());

        $taxSetStorageEntity->save();
    }
}
