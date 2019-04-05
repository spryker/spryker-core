<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business\TaxStoragePublisher;

use ArrayObject;
use Generated\Shared\Transfer\TaxRateStorageTransfer;
use Generated\Shared\Transfer\TaxSetStorageTransfer;
use Orm\Zed\Tax\Persistence\Base\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface;
use Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface;
use Spryker\Zed\TaxStorage\TaxStorageConfig;

class TaxStoragePublisher implements TaxStoragePublisherInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface
     */
    protected $taxStorageRepository;

    /**
     * @var \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface
     */
    protected $taxStorageEntityManager;

    /**
     * @var \Spryker\Zed\TaxStorage\TaxStorageConfig
     */
    protected $taxStorageConfig;

    /**
     * @param \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface $taxStorageRepository
     * @param \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface $entityManager
     * @param \Spryker\Zed\TaxStorage\TaxStorageConfig $taxStorageConfig
     */
    public function __construct(
        TaxStorageRepositoryInterface $taxStorageRepository,
        TaxStorageEntityManagerInterface $entityManager,
        TaxStorageConfig $taxStorageConfig
    ) {
        $this->taxStorageRepository = $taxStorageRepository;
        $this->taxStorageEntityManager = $entityManager;
        $this->taxStorageConfig = $taxStorageConfig;
    }

    /**
     * @param array $taxSetIds
     *
     * @return void
     */
    public function publishByTaxSetIds(array $taxSetIds): void
    {
        $spyTaxSets = $this->taxStorageRepository
            ->findTaxSetsByIds($taxSetIds);
        $spyTaxSetStorage = $this->taxStorageRepository
            ->findTaxSetStoragesByIds($taxSetIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($spyTaxSets, $spyTaxSetStorage): void {
            $this->storeDataSet($spyTaxSets, $spyTaxSetStorage);
        });
    }

    /**
     * @param array $taxSetIds
     *
     * @return void
     */
    public function unpublishByTaxSetIds(array $taxSetIds): void
    {
        $spyTaxSetStorages = $this->taxStorageRepository
            ->findTaxSetStoragesByIds($taxSetIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($spyTaxSetStorages): void {
            $this->executeUnpublishTransaction($spyTaxSetStorages);
        });
    }

    /**
     * @param array $taxRateIds
     *
     * @return void
     */
    public function publishByTaxRateIds(array $taxRateIds): void
    {
        $taxSetIds = $this->taxStorageRepository
            ->findTaxSetIdsByTaxRateIds($taxRateIds);

        $this->publishByTaxSetIds($taxSetIds);
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet[] $spyTaxSets
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage[] $spyTaxSetStorages
     *
     * @return void
     */
    protected function storeDataSet(array $spyTaxSets, array $spyTaxSetStorages): void
    {
        foreach ($spyTaxSets as $spyTaxSet) {
            $spyTaxSetStorage = $spyTaxSetStorages[$spyTaxSet->getIdTaxSet()] ?? (new SpyTaxSetStorage())
                    ->setFkTaxSet($spyTaxSet->getIdTaxSet());
            $this->createDataSet($spyTaxSet, $spyTaxSetStorage);
        }
    }

    /**
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage[] $spyTaxSetStorages
     *
     * @return void
     */
    protected function executeUnpublishTransaction(array $spyTaxSetStorages): void
    {
        foreach ($spyTaxSetStorages as $spyTaxSetStorage) {
            $this->taxStorageEntityManager->deleteTaxSetStorage($spyTaxSetStorage);
        }
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $spyTaxSet
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage|null $spyTaxSetStorage
     *
     * @return void
     */
    protected function createDataSet(SpyTaxSet $spyTaxSet, ?SpyTaxSetStorage $spyTaxSetStorage = null): void
    {
        if ($spyTaxSetStorage === null) {
            $spyTaxSetStorage = new SpyTaxSetStorage();
        }
        $spyTaxSetStorage = $this->mapSpyTaxSetToTaxSetStorage($spyTaxSet, $spyTaxSetStorage);
        $spyTaxSetStorage->setIsSendingToQueue(
            $this->taxStorageConfig->isSendingToQueue()
        );

        $this->taxStorageEntityManager->saveTaxSetStorage($spyTaxSetStorage);
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\Base\SpyTaxSet $spyTaxSet
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage|null $spyTaxSetStorage
     *
     * @return \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage
     */
    public function mapSpyTaxSetToTaxSetStorage(SpyTaxSet $spyTaxSet, ?SpyTaxSetStorage $spyTaxSetStorage = null): SpyTaxSetStorage
    {
        $taxSetStorageTransfer = new TaxSetStorageTransfer();
        $taxSetStorageTransfer->setIdTaxSetStorage($spyTaxSet->getIdTaxSet());
        $taxSetStorageTransfer->fromArray($spyTaxSet->toArray(), true);
        $taxSetStorageTransfer->setTaxRates(
            $this->mapSpyTaxRatesToTaxRateTransfers($spyTaxSet->getSpyTaxRates())
        );
        $spyTaxSetStorage->setData($taxSetStorageTransfer->toArray());

        return $spyTaxSetStorage;
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate[] $spyTaxRates
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\TaxRateStorageTransfer[]
     */
    protected function mapSpyTaxRatesToTaxRateTransfers(array $spyTaxRates): ArrayObject
    {
        $taxRateTransfers = new ArrayObject();

        foreach ($spyTaxRates as $spyTaxRate) {
            $taxRateTransfers->append(
                $this->mapSpyTaxRateToTaxRateStorageTransfer($spyTaxRate, new TaxRateStorageTransfer())
            );
        }

        return $taxRateTransfers;
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate $spyTaxRate
     * @param \Generated\Shared\Transfer\TaxRateStorageTransfer $taxRateStorageTransfer
     *
     * @return \Generated\Shared\Transfer\TaxRateStorageTransfer
     */
    protected function mapSpyTaxRateToTaxRateStorageTransfer(
        SpyTaxRate $spyTaxRate,
        TaxRateStorageTransfer $taxRateStorageTransfer
    ): TaxRateStorageTransfer {
        return $taxRateStorageTransfer
            ->fromArray($spyTaxRate->toArray(), true)
            ->setCountry($spyTaxRate->getCountry()->getName());
    }
}
