<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade;

use Generated\Shared\Transfer\CountryTransfer;

class MerchantRegistrationRequestToCountryFacadeBridge implements MerchantRegistrationRequestToCountryFacadeInterface
{
    /**
     * @var \Spryker\Zed\Country\Business\CountryFacadeInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\Country\Business\CountryFacadeInterface $countryFacade
     */
    public function __construct($countryFacade)
    {
        $this->countryFacade = $countryFacade;
    }

    public function getCountryByIso2Code(string $iso2Code): CountryTransfer
    {
        return $this->countryFacade->getCountryByIso2Code($iso2Code);
    }
}
