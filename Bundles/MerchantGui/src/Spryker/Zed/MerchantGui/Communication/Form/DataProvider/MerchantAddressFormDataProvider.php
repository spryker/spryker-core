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
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function getData(?int $idMerchantAddress = null): ?MerchantAddressTransfer
    {
        $merchantAddressTransfer = new MerchantAddressTransfer();
        if (!$idMerchantAddress) {
            return $merchantAddressTransfer;
        }

        $merchantAddressTransfer->setIdMerchantAddress($idMerchantAddress);

        return $this->merchantFacade->findMerchantAddressById($merchantAddressTransfer);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => MerchantAddressTransfer::class,
            MerchantAddressForm::OPTION_COUNTRY_CHOICES => $this->prepareCountryChoices(),
        ];
    }

    /**
     * @return array
     */
    protected function prepareCountryChoices(): array
    {
        $result = [];

        foreach ($this->countryFacade->getAvailableCountries()->getCountries() as $country) {
            $result[$country->getIdCountry()] = $country->getName();
        }

        return $result;
    }
}
