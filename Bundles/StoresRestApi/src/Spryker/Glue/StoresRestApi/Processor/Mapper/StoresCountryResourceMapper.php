<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\StoreCountryRestAttributesTransfer;
use Generated\Shared\Transfer\StoreRegionRestAttributesTransfer;

class StoresCountryResourceMapper implements StoresCountryResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer
     */
    public function mapCountryToStoresCountryRestAttributes(CountryTransfer $countryTransfer): StoreCountryRestAttributesTransfer
    {
        $storesCountryAttributes = (new StoreCountryRestAttributesTransfer())->fromArray(
            $countryTransfer->toArray(),
            true
        );

        $regions = new ArrayObject();
        foreach ($countryTransfer->getRegions() as $regionTransfer) {
            $regions->append((new StoreRegionRestAttributesTransfer())->fromArray(
                $regionTransfer->toArray(),
                true
            ));
        }
        $storesCountryAttributes->setRegions($regions);

        return $storesCountryAttributes;
    }
}
