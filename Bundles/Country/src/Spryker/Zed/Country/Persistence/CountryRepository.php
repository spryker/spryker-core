<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryRequestTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Country\Persistence\CountryPersistenceFactory getFactory()
 */
class CountryRepository extends AbstractRepository implements CountryRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CountryRequestTransfer $countryRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function findCountriesByIso2Codes(CountryRequestTransfer $countryRequestTransfer): CountryCollectionTransfer
    {
        $query = $this->getFactory()
            ->createCountryQuery()
            ->joinWithSpyRegion()
            ->filterByIso2Code_In($countryRequestTransfer->getIso2Codes());

        return $this->getFactory()
            ->createCountryMapper()
            ->mapCountryTransferCollection($this->buildQueryFromCriteria($query)->find());
    }
}
