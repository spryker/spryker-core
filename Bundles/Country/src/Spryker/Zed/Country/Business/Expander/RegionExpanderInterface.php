<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Expander;

use Generated\Shared\Transfer\CountryCollectionTransfer;

interface RegionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function expandCountryCollectionWithRegions(CountryCollectionTransfer $countryCollectionTransfer): CountryCollectionTransfer;
}
