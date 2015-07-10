<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\CountryCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Country\Business\CountryFacade;
use SprykerFeature\Zed\Country\Communication\Table\DetailsTable;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainerInterface;

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
     * @return DetailsTable
     */
    public function createDetailsTable()
    {
        $countryQuery = $this->getQueryContainer()->queryCountries();
        return $this->getFactory()->createTableDetailsTable($countryQuery);
    }
}
