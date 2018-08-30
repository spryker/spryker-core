<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Generated\Shared\Transfer\RegionCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Country\Persistence\CountryPersistenceFactory getFactory()
 */
class CountryRepository extends AbstractRepository implements CountryRepositoryInterface
{
    /**
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\RegionCollectionTransfer
     */
    public function findRegionsByCountryIso2Code(string $iso2Code): RegionCollectionTransfer
    {
        $query = $this->getFactory()
            ->createRegionQuery()
            ->useSpyCountryQuery()
                ->filterByIso2Code($iso2Code)
            ->endUse();

        return $this->getFactory()
            ->createRegionMapper()
            ->mapTransferCollection($this->buildQueryFromCriteria($query)->find());
    }
}
