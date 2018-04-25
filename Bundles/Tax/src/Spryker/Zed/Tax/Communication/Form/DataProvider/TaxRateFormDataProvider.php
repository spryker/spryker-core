<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Form\DataProvider;

use Generated\Shared\Transfer\TaxRateTransfer;
use Spryker\Zed\Tax\Communication\Form\TaxRateForm;
use Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface;

class TaxRateFormDataProvider
{
    /**
     * @var \Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface
     */
    protected $countryFacade;

    /**
     * @var \Generated\Shared\Transfer\TaxRateTransfer
     */
    protected $taxRateTransfer;

    /**
     * @param \Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface $countryFacade
     * @param \Generated\Shared\Transfer\TaxRateTransfer|null $taxRateTransfer
     */
    public function __construct(TaxToCountryBridgeInterface $countryFacade, ?TaxRateTransfer $taxRateTransfer = null)
    {
        $this->countryFacade = $countryFacade;
        $this->taxRateTransfer = $taxRateTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function getData()
    {
        return $this->taxRateTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            TaxRateForm::FIELD_COUNTRY => $this->createCountryList(),
        ];
    }

    /**
     * @return array
     */
    protected function createCountryList()
    {
        $countryCollection = $this->countryFacade->getAvailableCountries();
        $countries = [0 => 'No country'];
        foreach ($countryCollection->getCountries() as $countryTransfer) {
            $countries[$countryTransfer->getIdCountry()] = $countryTransfer->getName();
        }

        return $countries;
    }
}
