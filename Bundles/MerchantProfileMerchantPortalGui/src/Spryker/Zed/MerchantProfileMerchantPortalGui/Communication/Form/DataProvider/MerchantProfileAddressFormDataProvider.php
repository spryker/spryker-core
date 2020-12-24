<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\DataProvider;

use Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToCountryFacadeInterface;

class MerchantProfileAddressFormDataProvider implements MerchantProfileAddressFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToCountryFacadeInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\MerchantProfileMerchantPortalGui\Dependency\Facade\MerchantProfileMerchantPortalGuiToCountryFacadeInterface $countryFacade
     */
    public function __construct(MerchantProfileMerchantPortalGuiToCountryFacadeInterface $countryFacade)
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
            /** @var int $idCountry */
            $idCountry = $country->requireIdCountry()->getIdCountry();
            /** @var string $countryName */
            $countryName = $country->requireName()->getName();

            $countryChoices[$idCountry] = $countryName;
        }

        return $countryChoices;
    }
}
