<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business\TaxStoragePublisher;

use ArrayObject;
use Generated\Shared\Transfer\TaxRateStorageTransfer;
use Generated\Shared\Transfer\TaxSetDataStorageTransfer;
use Generated\Shared\Transfer\TaxSetStorageTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface;
use Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface;

class TaxStoragePublisher implements TaxStoragePublisherInterface
{
    use TransactionTrait;

    public const KEY_DELIMITER = ':';
    public const KEY_PREFIX = 'tax_set';

    /**
     * @var \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface
     */
    protected $taxStorageRepository;

    /**
     * @var \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface
     */
    protected $taxStorageEntityManager;

    /**
     * @param \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface $taxStorageRepository
     * @param \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface $entityManager
     */
    public function __construct(TaxStorageRepositoryInterface $taxStorageRepository, TaxStorageEntityManagerInterface $entityManager)
    {
        $this->taxStorageRepository = $taxStorageRepository;
        $this->taxStorageEntityManager = $entityManager;
    }

    /**
     * @param array $taxSetIds
     *
     * @return void
     */
    public function publishByTaxSetIds(array $taxSetIds): void
    {
        $taxSetTransfers = $this->taxStorageRepository->findTaxSetsByIds($taxSetIds);
        $taxSetStorageTransfers = $this->taxStorageRepository->findTaxSetStoragesByIds($taxSetIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($taxSetTransfers, $taxSetStorageTransfers) {
            return $this->storeDataSet($taxSetTransfers, $taxSetStorageTransfers);
        });
    }

    /**
     * @param array $taxSetIds
     *
     * @return void
     */
    public function unpublishByTaxSetIds(array $taxSetIds): void
    {
        $taxSetTransfers = $this->taxStorageRepository->findTaxSetsByIds($taxSetIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($taxSetTransfers) {
            return $this->executeUnpublishTransaction($taxSetTransfers);
        });
    }


    /**
     * @param array $taxRateIds
     *
     * @return void
     */
    public function publishByTaxRateIds(array $taxRateIds): void
    {
        $taxSetIds = $this->taxStorageRepository->findTaxSetIdsByTaxRateIds($taxRateIds);

        $this->publishByTaxSetIds($taxSetIds);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\TaxSetTransfer[] $taxSetTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\TaxSetStorageTransfer[] $taxSetStorageTransfers
     *
     * @return void
     */
    protected function storeDataSet(ArrayObject $taxSetTransfers, ArrayObject $taxSetStorageTransfers): void
    {
        foreach ($taxSetTransfers as $taxSetTransfer) {
            $this->createDataSet($taxSetTransfer);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\TaxSetStorageTransfer $taxSetStorageTransfers
     *
     * @return void
     */
    protected function executeUnpublishTransaction(ArrayObject $taxSetStorageTransfers): void
    {
        foreach ($taxSetStorageTransfers as $taxSetStorageTransfer) {
            if ($this->taxStorageEntityManager->deleteTaxSetStorage($taxSetStorageTransfer) === false) {
                throw new \Exception('Did not found SpyTaxSetStorage with Fk =' . $taxSetStorageTransfer->getFkTaxSet());
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @return void
     */
    protected function createDataSet(TaxSetTransfer $taxSetTransfer): void
    {
        $taxSetStorageTransfer = new TaxSetStorageTransfer();
        $taxSetStorageTransfer->fromArray($taxSetTransfer->toArray(), true);
        $taxSetStorageTransfer->setFkTaxSet($taxSetTransfer->getIdTaxSet());

        $taxRateStorageTransfers = new ArrayObject();
        foreach ($taxSetTransfer->getTaxRates() as $taxRate) {
            $taxRateStorageTransfer = (new TaxRateStorageTransfer())->fromArray(
                $taxRate->toArray(),
                true
            );
            $taxRateStorageTransfers->append($taxRateStorageTransfer);
        }
        $taxSetDataTransfer = new TaxSetDataStorageTransfer();
        $taxSetDataTransfer->setTaxRates($taxRateStorageTransfers);
        $taxSetStorageTransfer->setData($taxSetDataTransfer);

        $this->taxStorageEntityManager->saveTaxSetStorage($taxSetStorageTransfer);
    }
}
