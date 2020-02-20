<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\MerchantProfileAddressFormType;
use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToCountryFacadeInterface;

class MerchantProfileAddressFormDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToCountryFacadeInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToCountryFacadeInterface $countryFacade
     */
    public function __construct(
        MerchantProfileGuiPageToCountryFacadeInterface $countryFacade
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
     * @return string[]
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
