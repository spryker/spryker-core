<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\TaxRateStorageTransfer;
use Generated\Shared\Transfer\TaxSetStorageTransfer;
use Orm\Zed\Tax\Persistence\Base\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage;

class TaxStorageMapper
{
    /**
     * @param \Orm\Zed\Tax\Persistence\Base\SpyTaxSet $spyTaxSet
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage|null $spyTaxSetStorage
     *
     * @return \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage
     */
    public function mapSpyTaxSetToTaxSetStorage(SpyTaxSet $spyTaxSet, ?SpyTaxSetStorage $spyTaxSetStorage = null): SpyTaxSetStorage
    {
        $taxSetStorageTransfer = new TaxSetStorageTransfer();
        $taxSetStorageTransfer->setId($spyTaxSet->getIdTaxSet());
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
     * @return \Generated\Shared\Transfer\TaxRateStorageTransfer[]
     */
    public function mapSpyTaxRatesToTaxRateTransfers(array $spyTaxRates): array
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
