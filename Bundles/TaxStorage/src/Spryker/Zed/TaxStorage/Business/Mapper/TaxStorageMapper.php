<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\TaxRateStorageTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxRate;

class TaxStorageMapper implements TaxStorageMapperInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate[] $spyTaxRates
     *
     * @return \Generated\Shared\Transfer\TaxRateStorageTransfer[]
     */
    public function mapSpyTaxRatesToTransfer(iterable $spyTaxRates): iterable
    {
        $taxRateTransfer = new ArrayObject();

        foreach ($spyTaxRates as $spyTaxRate) {
            $taxRateTransfer->append(
                $this->mapSpyTaxRateToTaxRateStorageTransfer($spyTaxRate, new TaxRateStorageTransfer())
            );
        }

        return $taxRateTransfer;
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate $spyTaxRate
     * @param \Generated\Shared\Transfer\TaxRateStorageTransfer $taxRateStorageTransfer
     *
     * @return \Generated\Shared\Transfer\TaxRateStorageTransfer
     */
    public function mapSpyTaxRateToTaxRateStorageTransfer(
        SpyTaxRate $spyTaxRate,
        TaxRateStorageTransfer $taxRateStorageTransfer
    ): TaxRateStorageTransfer {
        return $taxRateStorageTransfer
            ->fromArray($spyTaxRate->toArray(), true)
            ->setCountry($spyTaxRate->getCountry()->getName());
    }
}
