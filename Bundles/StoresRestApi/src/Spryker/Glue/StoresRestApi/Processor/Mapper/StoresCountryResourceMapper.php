<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionCollectionTransfer;
use Generated\Shared\Transfer\StoreCountryRestAttributesTransfer;
use Generated\Shared\Transfer\StoreRegionRestAttributesTransfer;

class StoresCountryResourceMapper implements StoresCountryResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     * @param \Generated\Shared\Transfer\RegionCollectionTransfer $regionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer
     */
    public function mapCountryToStoresCountryRestAttributes(CountryTransfer $countryTransfer, RegionCollectionTransfer $regionCollectionTransfer): StoreCountryRestAttributesTransfer
    {
        $storesCountryAttributes = (new StoreCountryRestAttributesTransfer())->fromArray(
            $countryTransfer->toArray(),
            true
        );

        foreach ($regionCollectionTransfer->getRegions() as $regionTransfer) {
            $storesCountryAttributes->addRegions((new StoreRegionRestAttributesTransfer())->fromArray(
                $regionTransfer->toArray(),
                true
            ));
        }

        return $storesCountryAttributes;
    }
}
