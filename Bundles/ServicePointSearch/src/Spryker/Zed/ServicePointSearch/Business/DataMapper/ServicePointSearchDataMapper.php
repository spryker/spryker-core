<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Business\DataMapper;

use Generated\Shared\Search\ServicePointIndexMap;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\ServicePointSearch\ServicePointSearchConfig;

class ServicePointSearchDataMapper implements ServicePointSearchDataMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<string, mixed>
     */
    public function mapServicePointToSearchData(ServicePointTransfer $servicePointTransfer, StoreTransfer $storeTransfer): array
    {
        return [
            ServicePointIndexMap::TYPE => ServicePointSearchConfig::SERVICE_POINT_RESOURCE_NAME,
            ServicePointIndexMap::STORE => $storeTransfer->getName(),
            ServicePointIndexMap::SEARCH_RESULT_DATA => $this->getSearchResultData($servicePointTransfer),
            ServicePointIndexMap::FULL_TEXT_BOOSTED => $this->getFullTextBoostedData($servicePointTransfer),
            ServicePointIndexMap::FULL_TEXT => $this->getFullTextData($servicePointTransfer),
            ServicePointIndexMap::SUGGESTION_TERMS => $this->getSuggestionTermsData($servicePointTransfer),
            ServicePointIndexMap::COMPLETION_TERMS => $this->getCompletionTermsData($servicePointTransfer),
            ServicePointIndexMap::STRING_SORT => $this->getStringSortData($servicePointTransfer),
            ServicePointIndexMap::SERVICE_TYPES => $this->getServiceTypesData($servicePointTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return array<string, mixed>
     */
    public function getSearchResultData(ServicePointTransfer $servicePointTransfer): array
    {
        $searchResultData = [
            ServicePointTransfer::ID_SERVICE_POINT => $servicePointTransfer->getIdServicePointOrFail(),
            ServicePointTransfer::UUID => $servicePointTransfer->getUuidOrFail(),
            ServicePointTransfer::NAME => $servicePointTransfer->getNameOrFail(),
            ServicePointTransfer::KEY => $servicePointTransfer->getKeyOrFail(),
        ];

        $servicePointAddressTransfer = $servicePointTransfer->getAddress();

        if (!$servicePointAddressTransfer) {
            return $searchResultData;
        }

        $searchResultData[ServicePointTransfer::ADDRESS] = [
            ServicePointAddressTransfer::ID_SERVICE_POINT_ADDRESS => $servicePointAddressTransfer->getIdServicePointAddressOrFail(),
            ServicePointAddressTransfer::UUID => $servicePointAddressTransfer->getUuidOrFail(),
            ServicePointAddressTransfer::ADDRESS1 => $servicePointAddressTransfer->getAddress1OrFail(),
            ServicePointAddressTransfer::ADDRESS2 => $servicePointAddressTransfer->getAddress2OrFail(),
            ServicePointAddressTransfer::ADDRESS3 => $servicePointAddressTransfer->getAddress3(),
            ServicePointAddressTransfer::CITY => $servicePointAddressTransfer->getCityOrFail(),
            ServicePointAddressTransfer::ZIP_CODE => $servicePointAddressTransfer->getZipCodeOrFail(),
            ServicePointAddressTransfer::COUNTRY => [
                CountryTransfer::ISO2_CODE => $servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail(),
                CountryTransfer::NAME => $servicePointAddressTransfer->getCountryOrFail()->getNameOrFail(),
            ],
        ];

        $regionTransfer = $servicePointAddressTransfer->getRegion();

        if (!$regionTransfer) {
            return $searchResultData;
        }

        $searchResultData[ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::REGION] = [
            RegionTransfer::NAME => $regionTransfer->getNameOrFail(),
        ];

        return $searchResultData;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return list<string>
     */
    protected function getFullTextBoostedData(ServicePointTransfer $servicePointTransfer): array
    {
        return [
            $servicePointTransfer->getNameOrFail(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return list<string>
     */
    protected function getFullTextData(ServicePointTransfer $servicePointTransfer): array
    {
        $fullTextData = [$servicePointTransfer->getNameOrFail()];
        $servicePointAddressTransfer = $servicePointTransfer->getAddress();

        if (!$servicePointAddressTransfer) {
            return $fullTextData;
        }

        $fullTextData[] = $servicePointAddressTransfer->getCityOrFail();
        $fullTextData[] = $servicePointAddressTransfer->getZipCodeOrFail();
        $fullTextData[] = $servicePointAddressTransfer->getCountryOrFail()->getNameOrFail();
        $fullTextData[] = $this->getFullServicePointAddress($servicePointAddressTransfer);

        $regionTransfer = $servicePointAddressTransfer->getRegion();

        if (!$regionTransfer) {
            return $fullTextData;
        }

        $fullTextData[] = $regionTransfer->getNameOrFail();

        return $fullTextData;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return list<string>
     */
    protected function getSuggestionTermsData(ServicePointTransfer $servicePointTransfer): array
    {
        return [
            $servicePointTransfer->getNameOrFail(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return list<string>
     */
    protected function getCompletionTermsData(ServicePointTransfer $servicePointTransfer): array
    {
        return [
            $servicePointTransfer->getNameOrFail(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return array<string, string>
     */
    protected function getStringSortData(ServicePointTransfer $servicePointTransfer): array
    {
        $servicePointAddressTransfer = $servicePointTransfer->getAddress();

        if (!$servicePointAddressTransfer) {
            return [];
        }

        return [
            ServicePointAddressTransfer::CITY => $servicePointAddressTransfer->getCityOrFail(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     *
     * @return string
     */
    protected function getFullServicePointAddress(ServicePointAddressTransfer $servicePointAddressTransfer): string
    {
        return trim(sprintf(
            '%s %s %s',
            $servicePointAddressTransfer->getAddress1OrFail(),
            $servicePointAddressTransfer->getAddress2OrFail(),
            $servicePointAddressTransfer->getAddress3() ?? '',
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return list<string>
     */
    protected function getServiceTypesData(ServicePointTransfer $servicePointTransfer): array
    {
        if (!count($servicePointTransfer->getServices())) {
            return [];
        }

        $serviceTypesData = [];
        foreach ($servicePointTransfer->getServices() as $serviceTransfer) {
            $serviceTypesData[] = $serviceTransfer->getServiceTypeOrFail()->getKeyOrFail();
        }

        return array_unique($serviceTypesData);
    }
}
