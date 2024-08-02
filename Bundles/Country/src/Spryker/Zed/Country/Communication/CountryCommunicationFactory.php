<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Communication;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Spryker\Zed\Country\Communication\Table\CountryTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Country\CountryConfig getConfig()
 * @method \Spryker\Zed\Country\Persistence\CountryRepositoryInterface getRepository()
 * @method \Spryker\Zed\Country\Business\CountryFacadeInterface getFacade()
 * @method \Spryker\Zed\Country\Persistence\CountryEntityManagerInterface getEntityManager()
 */
class CountryCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Country\Communication\Table\CountryTable
     */
    public function createCountryTable(): CountryTable
    {
        return new CountryTable(
            $this->getCountryQuery(),
        );
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery<mixed>
     */
    public function getCountryQuery(): SpyCountryQuery
    {
        return SpyCountryQuery::create();
    }
}
