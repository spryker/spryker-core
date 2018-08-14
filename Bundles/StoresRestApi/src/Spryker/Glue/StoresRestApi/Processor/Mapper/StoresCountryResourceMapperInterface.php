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
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface StoresCountryResourceMapperInterface
{
    /**
     * @param CountryTransfer $countryTransfer
     * @param array regions
     *
     * @return \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer
     */
    public function mapCountryToStoresCountryRestAttributes(CountryTransfer $countryTransfer, array $regions): StoreCountryRestAttributesTransfer;
}
