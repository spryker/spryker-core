<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\CountryCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Country\Business\CountryFacade;
use SprykerFeature\Zed\Country\Communication\Form\CountryForm;
use SprykerFeature\Zed\Country\Communication\Table\DetailsTable;
use SprykerFeature\Zed\Country\CountryDependencyProvider;
use SprykerFeature\Zed\Country\Communication\Table\CountryTable;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;

/**
 * @method CountryCommunication getFactory()
 */
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

    /**
     * @return CountryForm
     */
    public function createCountryForm()
    {
        $countryQuery = $this->getQueryContainer()->queryCountries();
        $userQuery = $this->getUserQueryContainer()->queryUsers();

        return $this->getFactory()->createFormCountryForm($countryQuery, $userQuery);
    }

    /**
     * @return UserQueryContainer
     */
    protected function getUserQueryContainer()
    {
        return $this->getProvidedDependency(CountryDependencyProvider::USER_QUERY_CONTAINER);
    }

}
