<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\TaxRateStorageTransfer;
use Generated\Shared\Transfer\TaxSetStorageTransfer;
use Orm\Zed\Tax\Persistence\Base\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage;

class TaxStorageMapper
{
    /**
     * @param \Orm\Zed\Tax\Persistence\Base\SpyTaxSet[] $spyTaxSets
     *
     * @return \Generated\Shared\Transfer\TaxSetStorageTransfer[]
     */
    public function mapSpyTaxSetsToTaxSetStorageTransfers(array $spyTaxSets): array
    {
        $taxSetStorageTransfers = [];
        foreach ($spyTaxSets as $spyTaxSet) {
            $taxSetStorageTransfers[] = $this->mapSpyTaxSetToTaxSetStorageTransfer($spyTaxSet, new TaxSetStorageTransfer());
        }

        return $taxSetStorageTransfers;
    }

    /**
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage[] $spyTaxSetStorages
     *
     * @return \Generated\Shared\Transfer\TaxSetStorageTransfer[]
     */
    public function mapSpyTaxSetStoragesToTaxSetStorageTransfers(array $spyTaxSetStorages): array
    {
        $taxSetStorageTransfers = [];
        foreach ($spyTaxSetStorages as $spyTaxSetStorage) {
            $taxSetStorageTransfers[] = $this->mapSpyTaxSetStorageToTaxSetStorageTransfer($spyTaxSetStorage, new TaxSetStorageTransfer());
        }

        return $taxSetStorageTransfers;
    }

    /**
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage[] $spyTaxSetStorages
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapSpyTaxSetStoragesToSynchronizationDataTransfer(array $spyTaxSetStorages): array
    {
        $synchronizationDataTransfers = [];

        foreach ($spyTaxSetStorages as $spyTaxSetStorage) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            /** @var string $data */
            $data = $spyTaxSetStorage->getData();
            $synchronizationDataTransfer->setData($data);
            $synchronizationDataTransfer->setKey($spyTaxSetStorage->getKey());
            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage $spyTaxSetStorage
     * @param \Generated\Shared\Transfer\TaxSetStorageTransfer $taxSetStorageTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetStorageTransfer
     */
    protected function mapSpyTaxSetStorageToTaxSetStorageTransfer(SpyTaxSetStorage $spyTaxSetStorage, TaxSetStorageTransfer $taxSetStorageTransfer): TaxSetStorageTransfer
    {
        $taxSetStorageTransfer->setIdTaxSet($spyTaxSetStorage->getFkTaxSet());

        return $taxSetStorageTransfer;
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\Base\SpyTaxSet $spyTaxSet
     * @param \Generated\Shared\Transfer\TaxSetStorageTransfer $taxSetStorageTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetStorageTransfer
     */
    protected function mapSpyTaxSetToTaxSetStorageTransfer(SpyTaxSet $spyTaxSet, TaxSetStorageTransfer $taxSetStorageTransfer): TaxSetStorageTransfer
    {
        $taxSetStorageTransfer->fromArray($spyTaxSet->toArray(), true);
        $taxSetStorageTransfer->setTaxRates(
            $this->mapSpyTaxRatesToTaxRateTransfers($spyTaxSet->getSpyTaxRates()->getArrayCopy())
        );

        return $taxSetStorageTransfer;
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
        $taxRateStorageTransfer->fromArray($spyTaxRate->toArray(), true);
        if ($spyTaxRate->getCountry() !== null) {
            $taxRateStorageTransfer->setCountry($spyTaxRate->getCountry()->getIso2Code());
        }

        return $taxRateStorageTransfer;
    }
}
