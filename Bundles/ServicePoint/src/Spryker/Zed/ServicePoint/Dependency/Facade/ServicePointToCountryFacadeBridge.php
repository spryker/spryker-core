<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Dependency\Facade;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryCriteriaTransfer;

class ServicePointToCountryFacadeBridge implements ServicePointToCountryFacadeInterface
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

    /**
     * @param \Generated\Shared\Transfer\CountryCriteriaTransfer $countryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountryCollection(
        CountryCriteriaTransfer $countryCriteriaTransfer
    ): CountryCollectionTransfer {
        return $this->countryFacade->getCountryCollection($countryCriteriaTransfer);
    }
}
