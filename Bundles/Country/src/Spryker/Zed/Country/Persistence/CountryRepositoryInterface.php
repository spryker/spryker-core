<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence;

use Generated\Shared\Transfer\RegionCollectionTransfer;

interface CountryRepositoryInterface
{
    /**
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\RegionCollectionTransfer
     */
    public function getRegionsByCountryIso2Code(string $iso2Code): RegionCollectionTransfer;
}
