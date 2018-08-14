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
use Orm\Zed\Country\Persistence\Map\SpyRegionTableMap;
use Spryker\Glue\StoresRestApi\StoresRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class StoresCountryResourceMapper implements StoresCountryResourceMapperInterface
{
    /**
     * @param CountryTransfer $countryTransfer
     * @param array regions
     *
     * @return \Generated\Shared\Transfer\StoreCountryRestAttributesTransfer
     */
    public function mapCountryToStoresCountryRestAttributes(CountryTransfer $countryTransfer, array $regions): StoreCountryRestAttributesTransfer
    {
        $storesCountryAttributes = (new StoreCountryRestAttributesTransfer())
            ->setName($countryTransfer->getName())
            ->setIso2Code($countryTransfer->getIso2Code())
            ->setIso3Code($countryTransfer->getIso3Code());

        if ($countryTransfer->getPostalCodeMandatory()) {
            $storesCountryAttributes
                ->setPostalCodeMandatory($countryTransfer->getPostalCodeMandatory())
                ->setPostalCodeRegex($countryTransfer->getPostalCodeRegex());
        }

        foreach ($regions as $region) {
            $storesCountryAttributes->addRegions((new StoreRegionRestAttributesTransfer())
                ->setName($region[SpyRegionTableMap::COL_NAME])
                ->setIdentifier($region[SpyRegionTableMap::COL_ISO2_CODE]));

        }

        return $storesCountryAttributes;
    }
}
