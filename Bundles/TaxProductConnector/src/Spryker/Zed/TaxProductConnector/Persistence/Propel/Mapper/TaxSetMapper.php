<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxSet;

class TaxSetMapper implements TaxSetMapperInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function mapTaxSetEntityToTaxSetTransfer(SpyTaxSet $taxSetEntity): TaxSetTransfer
    {
        $taxSetTransfer = new TaxSetTransfer();
        $taxSetTransfer->fromArray($taxSetEntity->toArray(), true);
        foreach ($taxSetEntity->getSpyTaxRates() as $taxRate) {
            $taxRateTransfer = (new TaxRateTransfer())->fromArray($taxRate->toArray(), true);
            if ($taxRate->getCountry()) {
                $countryTransfer = (new CountryTransfer())->fromArray(
                    $taxRate->getCountry()->toArray(),
                    true
                );
                $taxRateTransfer->setCountry($countryTransfer);
            }
            $taxSetTransfer->addTaxRate($taxRateTransfer);
        }

        return $taxSetTransfer;
    }
}
