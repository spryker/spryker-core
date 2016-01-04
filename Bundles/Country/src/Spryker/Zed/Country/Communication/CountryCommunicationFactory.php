<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Country\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Country\Business\CountryFacade;
use Spryker\Zed\Country\Communication\Form\CountryForm;
use Spryker\Zed\Country\CountryDependencyProvider;
use Spryker\Zed\Country\Communication\Table\CountryTable;
use Spryker\Zed\User\Persistence\UserQueryContainer;
use Spryker\Zed\Country\CountryConfig;
use Spryker\Zed\Country\Persistence\CountryQueryContainer;
use Symfony\Component\Form\FormInterface;

/**
 * @method CountryConfig getConfig()
 * @method CountryQueryContainer getQueryContainer()
 */
class CountryCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return CountryTable
     */
    public function createCountryTable()
    {
        $countryQuery = $this->getQueryContainer()->queryCountries();

        return new CountryTable($countryQuery);
    }

    /**
     * @return UserQueryContainer
     */
    protected function getUserQueryContainer()
    {
        return $this->getProvidedDependency(CountryDependencyProvider::QUERY_CONTAINER_USER);
    }

}
