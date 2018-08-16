<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\RegionCollectionTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Generated\Shared\Transfer\SpyRegionEntityTransfer;

class RegionMapper implements RegionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyRegionEntityTransfer[] $regionEntityTransfers
     *
     * @return \Generated\Shared\Transfer\RegionCollectionTransfer
     */
    public function mapTransferCollection(array $regionEntityTransfers): RegionCollectionTransfer
    {
        $regionCollectionTransfer = new RegionCollectionTransfer();
        foreach ($regionEntityTransfers as $regionEntityTransfer) {
            $regionCollectionTransfer->addRegions(
                $this->mapRegionTransfer(
                    $regionEntityTransfer
                )
            );
        }

        return $regionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyRegionEntityTransfer $regionEntityTransfer
     *
     * @return \Generated\Shared\Transfer\RegionTransfer
     */
    public function mapRegionTransfer(SpyRegionEntityTransfer $regionEntityTransfer): RegionTransfer
    {
        $regionTransfer = (new RegionTransfer())
            ->fromArray($regionEntityTransfer->toArray(), true);

        return $regionTransfer;
    }
}
