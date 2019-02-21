<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantAddressTransfer;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantAddressForm;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToCountryFacadeInterface;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;

class MerchantAddressFormDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToCountryFacadeInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToCountryFacadeInterface $countryFacade
     */
    public function __construct(
        MerchantGuiToMerchantFacadeInterface $merchantFacade,
        MerchantGuiToCountryFacadeInterface $countryFacade
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param int|null $idMerchantAddress
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer|null
     */
    public function getData(?int $idMerchantAddress = null): ?MerchantAddressTransfer
    {
        if ($idMerchantAddress === null) {
            return null;
        }

        return $this->merchantFacade->findMerchantAddressByIdMerchantAddress($idMerchantAddress);
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
