<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantAddressTransfer;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantAddressForm;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToCountryFacadeInterface;

class MerchantAddressFormDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToCountryFacadeInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToCountryFacadeInterface $countryFacade
     */
    public function __construct(
        MerchantGuiToCountryFacadeInterface $countryFacade
    ) {
        $this->countryFacade = $countryFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => MerchantAddressTransfer::class,
            'label' => false,
            MerchantAddressForm::OPTION_COUNTRY_CHOICES => $this->prepareCountryChoices(),
        ];
    }

    /**
     * @return array
     */
    protected function prepareCountryChoices(): array
    {
        $countryChoices = [];

        foreach ($this->countryFacade->getAvailableCountries()->getCountries() as $country) {
            $countryChoices[$country->getIdCountry()] = $country->getName();
        }

        return $countryChoices;
    }
}
