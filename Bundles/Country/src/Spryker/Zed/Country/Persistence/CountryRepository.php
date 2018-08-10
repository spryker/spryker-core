<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Country\Persistence\Map\SpyRegionTableMap;
use Orm\Zed\Country\Persistence\SpyCountry;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Country\Persistence\CountryPersistenceFactory getFactory()
 */
class CountryRepository extends AbstractRepository implements CountryRepositoryInterface
{
    /**
     * @param string $iso2Code
     *
     * @return string[]
     */
    public function getRegionsByCountryIso2Code(string $iso2Code): array
    {
        return $this->getFactory()
            ->createRegionQuery()
            ->filterByFkCountry($this->findCountryByIso2Code($iso2Code))
            ->select([
                SpyRegionTableMap::COL_NAME,
                SpyRegionTableMap::COL_ISO2_CODE,
            ])
            ->find()
            ->toArray();
    }

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    protected function findCountryByIso2Code(string $iso2Code): int
    {
        return $this->getFactory()
            ->createCountryQuery()
            ->filterByIso2Code($iso2Code)
            ->select([SpyCountryTableMap::COL_ID_COUNTRY])
            ->findOne();
    }
}
