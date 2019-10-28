<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Spryker\Zed\MerchantProfileGui\Communication\Form\MerchantProfileAddressFormType;
use Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToCountryFacadeInterface;

class MerchantProfileAddressFormDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToCountryFacadeInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\MerchantProfileGui\Dependency\Facade\MerchantProfileGuiToCountryFacadeInterface $countryFacade
     */
    public function __construct(
        MerchantProfileGuiToCountryFacadeInterface $countryFacade
    ) {
        $this->countryFacade = $countryFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => MerchantProfileAddressTransfer::class,
            'label' => false,
            MerchantProfileAddressFormType::OPTION_COUNTRY_CHOICES => $this->prepareCountryChoices(),
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
