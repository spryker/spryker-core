<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Tax\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\TaxRateBuilder;
use Generated\Shared\DataBuilder\TaxSetBuilder;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class TaxHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    protected const ISO2_COUNTRY_DE = 'DE';

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function haveTaxSet(array $seedData = []): TaxSetTransfer
    {
        $taxSetTransfer = (new TaxSetBuilder($seedData))->build();

        $taxSetTransfer = $this->getLocator()->tax()->facade()->createTaxSet($taxSetTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($taxSetTransfer) {
            $this->getLocator()->tax()->facade()->deleteTaxSet($taxSetTransfer->getIdTaxSet());
        });

        return $taxSetTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function haveTaxRate(array $seedData = []): TaxRateTransfer
    {
        if (!isset($seedData['fkTaxSet'])) {
            $seedData['fkTaxSet'] = $this->haveTaxSet()->getIdTaxSet();
        }

        $countryTransfer = $this->haveCountry();

        /** @var \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer */
        $taxRateTransfer = (new TaxRateBuilder($seedData))->build();
        $taxRateTransfer->setCountry($countryTransfer);
        $taxRateTransfer->setFkCountry($countryTransfer->getIdCountry());

        $taxRateTransfer = $this->getLocator()->tax()->facade()->createTaxRate($taxRateTransfer);
        $this->getLocator()->tax()->facade()->addTaxRateToTaxSet($seedData['fkTaxSet'], $taxRateTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($taxRateTransfer) {
            $this->getLocator()->tax()->facade()->deleteTaxRate($taxRateTransfer->getIdTaxRate());
        });

        return $taxRateTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function haveTaxSetWithTaxRates(): TaxSetTransfer
    {
        $taxSetTransfer = $this->haveTaxSet();
        $taxRateTransfer = $this->haveTaxRate(['fkTaxSet' => $taxSetTransfer->getIdTaxSet()]);

        $taxSetTransfer->addTaxRate($taxRateTransfer);

        return $taxSetTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    protected function haveCountry(): CountryTransfer
    {
        return $this->getLocator()->country()->facade()->getCountryByIso2Code(static::ISO2_COUNTRY_DE);
    }
}
