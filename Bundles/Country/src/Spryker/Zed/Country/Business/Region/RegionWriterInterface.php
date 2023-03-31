<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Region;

use Generated\Shared\Transfer\RegionTransfer;

interface RegionWriterInterface
{
    /**
     * @param string $isoCode
     * @param int $fkCountry
     * @param string $regionName
     *
     * @return \Generated\Shared\Transfer\RegionTransfer
     */
    public function createRegion(string $isoCode, int $fkCountry, string $regionName): RegionTransfer;
}
