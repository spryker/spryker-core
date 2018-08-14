<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Region;

use Generated\Shared\Transfer\RegionCollectionTransfer;

interface RegionMapperInterface
{
    /**
     * @param array $regions
     *
     * @return \Generated\Shared\Transfer\RegionCollectionTransfer
     */
    public function mapRegionsToRegionCollection(array $regions): RegionCollectionTransfer;
}
