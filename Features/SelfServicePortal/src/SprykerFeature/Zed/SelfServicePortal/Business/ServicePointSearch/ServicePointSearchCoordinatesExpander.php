<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\ServicePointSearch;

use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;

class ServicePointSearchCoordinatesExpander implements ServicePointSearchCoordinatesExpanderInterface
{
    /**
     * @var string
     */
    protected const KEY_SEARCH_RESULT_DATA = 'search-result-data';

    /**
     * @param array<string, mixed> $searchData
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return array<string, mixed>
     */
    public function expandWithCoordinates(array $searchData, ServicePointTransfer $servicePointTransfer): array
    {
        $servicePointAddressTransfer = $servicePointTransfer->getAddress();

        if (!$servicePointAddressTransfer) {
            return $searchData;
        }

        if (!isset($searchData[static::KEY_SEARCH_RESULT_DATA][ServicePointTransfer::ADDRESS])) {
            return $searchData;
        }

        $latitude = $servicePointAddressTransfer->getLatitude();
        $longitude = $servicePointAddressTransfer->getLongitude();

        if ($latitude !== null) {
            $searchData[static::KEY_SEARCH_RESULT_DATA][ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::LATITUDE] = $latitude;
        }

        if ($longitude !== null) {
            $searchData[static::KEY_SEARCH_RESULT_DATA][ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::LONGITUDE] = $longitude;
        }

        return $searchData;
    }
}
