<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Zed\Country\Business\CountryFacade;
use Spryker\Zed\Country\Communication\Form\CountryForm;
use Spryker\Zed\Country\CountryDependencyProvider;
use Spryker\Zed\Country\Communication\Table\CountryTable;
use Spryker\Zed\User\Persistence\UserQueryContainer;

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
