<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Country\Business\CountryFacade;
use SprykerFeature\Zed\Country\Communication\Form\CountryForm;
use SprykerFeature\Zed\Country\CountryDependencyProvider;
use SprykerFeature\Zed\Country\Communication\Table\CountryTable;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;

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

        return new CountryTable($countryQuery);
    }

    /**
     * @return CountryForm
     */
    public function createCountryForm()
    {
        $countryQuery = $this->getQueryContainer()->queryCountries();
        $userQuery = $this->getUserQueryContainer()->queryUsers();

        return new CountryForm($countryQuery, $userQuery);
    }

    /**
     * @return UserQueryContainer
     */
    protected function getUserQueryContainer()
    {
        return $this->getProvidedDependency(CountryDependencyProvider::USER_QUERY_CONTAINER);
    }

}
