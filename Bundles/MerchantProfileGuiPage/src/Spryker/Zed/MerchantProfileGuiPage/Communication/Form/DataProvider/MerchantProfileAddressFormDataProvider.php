<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form\DataProvider;

use Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToCountryFacadeInterface;

class MerchantProfileAddressFormDataProvider implements MerchantProfileAddressFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToCountryFacadeInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\MerchantProfileGuiPage\Dependency\Facade\MerchantProfileGuiPageToCountryFacadeInterface $countryFacade
     */
    public function __construct(MerchantProfileGuiPageToCountryFacadeInterface $countryFacade)
    {
        $this->countryFacade = $countryFacade;
    }

    /**
     * @return string[]
     */
    public function getCountryChoices(): array
    {
        $countryChoices = [];

        foreach ($this->countryFacade->getAvailableCountries()->getCountries() as $country) {
            $countryChoices[$country->getIdCountry()] = $country->getName();
        }

        return $countryChoices;
    }
}
