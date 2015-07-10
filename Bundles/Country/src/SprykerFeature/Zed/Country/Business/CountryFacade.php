<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Psr\Log\LoggerInterface;

/**
 * @method CountryDependencyContainer getDependencyContainer()
 */
class CountryFacade extends AbstractFacade
{

    /**
     * @param LoggerInterface $messenger
     */
    public function install(LoggerInterface $messenger)
    {
        $this->getDependencyContainer()->createInstaller($messenger)->install();
    }

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function getIdCountryByIso2Code($iso2Code)
    {
        return $this->getDependencyContainer()->createCountryManager()->getIdCountryByIso2Code($iso2Code);
    }

}
