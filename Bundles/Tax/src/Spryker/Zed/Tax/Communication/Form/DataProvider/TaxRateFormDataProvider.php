<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Form\DataProvider;

use Generated\Shared\Transfer\TaxRateTransfer;
use Spryker\Zed\Tax\Business\TaxFacadeInterface;
use Spryker\Zed\Tax\Communication\Form\TaxRateForm;
use Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface;
use Spryker\Zed\Tax\Dependency\Facade\TaxToLocaleFacadeInterface;

class TaxRateFormDataProvider
{
    /**
     * @var \Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface
     */
    protected TaxToCountryBridgeInterface $countryFacade;

    /**
     * @var \Spryker\Zed\Tax\Business\TaxFacadeInterface
     */
    protected TaxFacadeInterface $taxFacade;

    /**
     * @var \Spryker\Zed\Tax\Dependency\Facade\TaxToLocaleFacadeInterface
     */
    protected TaxToLocaleFacadeInterface $localeFacade;

    /**
     * @var \Generated\Shared\Transfer\TaxRateTransfer|null
     */
    protected ?TaxRateTransfer $taxRateTransfer;

    /**
     * @param \Spryker\Zed\Tax\Dependency\Facade\TaxToCountryBridgeInterface $countryFacade
     * @param \Spryker\Zed\Tax\Business\TaxFacadeInterface $taxFacade
     * @param \Spryker\Zed\Tax\Dependency\Facade\TaxToLocaleFacadeInterface $localeFacade
     * @param \Generated\Shared\Transfer\TaxRateTransfer|null $taxRateTransfer
     */
    public function __construct(
        TaxToCountryBridgeInterface $countryFacade,
        TaxFacadeInterface $taxFacade,
        TaxToLocaleFacadeInterface $localeFacade,
        ?TaxRateTransfer $taxRateTransfer = null
    ) {
        $this->countryFacade = $countryFacade;
        $this->taxFacade = $taxFacade;
        $this->localeFacade = $localeFacade;
        $this->taxRateTransfer = $taxRateTransfer;
    }

    /**
     * @param int|null $idTaxRate
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer|null
     */
    public function getData(?int $idTaxRate = null): ?TaxRateTransfer
    {
        if (!$idTaxRate) {
            return $this->taxRateTransfer;
        }

        return $this->taxFacade->findTaxRate($idTaxRate);
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            TaxRateForm::OPTION_COUNTRIES => array_flip($this->createCountryList()),
            TaxRateForm::OPTION_LOCALE => $this->localeFacade->getCurrentLocaleName(),
            'data_class' => TaxRateTransfer::class,
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
