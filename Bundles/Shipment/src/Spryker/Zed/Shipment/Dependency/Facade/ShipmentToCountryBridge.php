<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Dependency\Facade;

class ShipmentToCountryBridge implements ShipmentToCountryInterface
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
     * @param string $iso2Code
     *
     * @return int
     */
    public function getIdCountryByIso2Code($iso2Code)
    {
        return $this->countryFacade->getIdCountryByIso2Code($iso2Code);
    }
}
