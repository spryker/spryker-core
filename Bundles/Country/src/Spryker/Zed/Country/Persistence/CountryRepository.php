<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Country\Persistence\CountryPersistenceFactory getFactory()
 */
class CountryRepository extends AbstractRepository implements CountryRepositoryInterface
{
    /**
     * @param string[] $iso2Codes
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function findCountriesByIso2Codes(array $iso2Codes): CountryCollectionTransfer
    {
        $countryQuery = $this->getFactory()
            ->createCountryQuery()
            ->joinWithSpyRegion(Criteria::LEFT_JOIN)
            ->filterByIso2Code_In($iso2Codes);
        $countries = $this->buildQueryFromCriteria($countryQuery)->find();

        return $this->getFactory()
            ->createCountryMapper()
            ->mapCountryTransferCollection($countries);
    }
}
