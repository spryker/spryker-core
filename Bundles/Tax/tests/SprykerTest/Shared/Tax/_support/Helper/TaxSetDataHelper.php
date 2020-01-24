<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Tax\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\TaxRateBuilder;
use Generated\Shared\DataBuilder\TaxSetBuilder;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class TaxSetDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveTaxSet(array $override = []): TaxSetTransfer
    {
        $taxSetTransfer = (new TaxSetBuilder($override))->build();

        $taxSetTransfer->setIdTaxSet(
            $this->saveTaxSet($taxSetTransfer)
        );

        return $taxSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @return int
     */
    protected function saveTaxSet(TaxSetTransfer $taxSetTransfer): int
    {
        $taxSetEntity = new SpyTaxSet();
        $taxSetEntity->fromArray($taxSetTransfer->toArray());
        $taxSetEntity->save();

        return $taxSetEntity->getIdTaxSet();
    }

    /**
     * @param array $overrideSet
     * @param array $overrideRates
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveTaxSetWithTaxRates(array $overrideSet = [], array $overrideRates = [[]]): TaxSetTransfer
    {
        $taxSetTransfer = $this->haveTaxSet($overrideSet);

        foreach ($overrideRates as $overrideTaxRate) {
            $taxRateTransfer = $this->createTaxRateTransfer($overrideTaxRate);
            $this->addTaxRateToTaxSet($taxSetTransfer, $taxRateTransfer);
        }

        return $taxSetTransfer;
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    protected function createTaxRateTransfer(array $override = []): TaxRateTransfer
    {
        $taxRateTransfer = (new TaxRateBuilder($override))->build();

        $taxRateEntity = new SpyTaxRate();
        $taxRateEntity->fromArray($taxRateTransfer->toArray());
        $taxRateEntity->save();

        $taxRateTransfer->setIdTaxRate($taxRateEntity->getIdTaxRate());

        if ($taxRateTransfer->getFkCountry() === null) {
            $countryTransfer = $this->haveCountry();
            $taxRateTransfer->setFkCountry($countryTransfer->getIdCountry());
            $taxRateTransfer->setCountry($countryTransfer);
        }

        return $taxRateTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return void
     */
    protected function addTaxRateToTaxSet(TaxSetTransfer $taxSetTransfer, TaxRateTransfer $taxRateTransfer): void
    {
        $taxSetTaxEntity = new SpyTaxSetTax();
        $taxSetTaxEntity->setFkTaxSet($taxSetTransfer->getIdTaxSet())
            ->setFkTaxRate($taxRateTransfer->getIdTaxRate());
        $taxSetTaxEntity->save();

        $taxSetTransfer->addTaxRate($taxRateTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    protected function haveCountry(): CountryTransfer
    {
        return $this->getLocator()->country()->facade()->getCountryByIso2Code('DE');
    }
}
