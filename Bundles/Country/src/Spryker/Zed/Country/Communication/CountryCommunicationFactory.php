<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Communication;

use Spryker\Zed\Country\Communication\Table\CountryTable;
use Spryker\Zed\Country\CountryDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Country\CountryConfig getConfig()
 * @method \Spryker\Zed\Country\Persistence\CountryQueryContainer getQueryContainer()
 */
class CountryCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Country\Communication\Table\CountryTable
     */
    public function createCountryTable()
    {
        $countryQuery = $this->getQueryContainer()->queryCountries();

        return new CountryTable($countryQuery);
    }

    /**
     * @return \Spryker\Zed\User\Persistence\UserQueryContainer
     */
    protected function getUserQueryContainer()
    {
        return $this->getProvidedDependency(CountryDependencyProvider::QUERY_CONTAINER_USER);
    }

}
