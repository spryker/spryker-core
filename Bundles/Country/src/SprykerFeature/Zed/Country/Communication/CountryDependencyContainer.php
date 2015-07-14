<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Country\Business\CountryFacade;
use SprykerFeature\Zed\Country\Communication\Table\DetailsTable;

class CountryDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return CountryFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->country()->facade();
    }


    /**
     * @return CountryTable
     */
    public function createCountryTable()
    {
        $countryQuery = $this->getQueryContainer()->queryCountries();

        return $this->getFactory()->createTableCountryTable($countryQuery);
    }
}
