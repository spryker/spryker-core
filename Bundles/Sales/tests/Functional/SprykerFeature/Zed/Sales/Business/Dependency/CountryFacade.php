<?php

namespace Functional\SprykerFeature\Zed\Sales\Business\Dependency;

use SprykerFeature\Zed\Country\Business\CountryFacade as SprykerCountryFacade;
use SprykerFeature\Zed\Sales\Dependency\Facade\SalesToCountryInterface;

class CountryFacade extends SprykerCountryFacade implements SalesToCountryInterface
{
}
