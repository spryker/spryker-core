<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Form\DataProvider;

use Spryker\Zed\Tax\Communication\Form\TaxRateForm;
use Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface;

class TaxRateFormDataProvider
{
    /**
     * @var \Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface $countryFacade
     */
    public function __construct(TaxToCountryBridgeInterface $countryFacade)
    {
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param int $idTaxRate
     *
     * @return array
     */
    public function getData($idTaxRate = null)
    {
       return [
           TaxRateForm::FIELD_COUNTRY => $this->createCountryList()
       ];
    }

    /**
     * @return array
     */
    protected function createCountryList()
    {
        $countryCollection = $this->countryFacade->getAvailableCountries();
        $countries = [];
        foreach ($countryCollection->getCountries() as $countryTransfer) {
            $countries[$countryTransfer->getIdCountry()] = $countryTransfer->getName();
        }

        return $countries;
    }
}
