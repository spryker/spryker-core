<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\RegionCollectionTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Orm\Zed\Country\Persistence\SpyRegion;
use Propel\Runtime\Collection\ObjectCollection;

class RegionMapper implements RegionMapperMapperInterface
{
     /**
      * @param \Propel\Runtime\Collection\ObjectCollection $regionEntityCollection
      *
      * @return \Generated\Shared\Transfer\RegionCollectionTransfer
      */
    public function mapTransferCollection(ObjectCollection $regionEntityCollection): RegionCollectionTransfer
    {
        $regionCollectionTransfer = new RegionCollectionTransfer();
        foreach ($regionEntityCollection as $regionEntity) {
            $regionCollectionTransfer->addRegions(
                $this->mapRegionTransfer(
                    $regionEntity
                )
            );
        }

        return $regionCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Country\Persistence\SpyRegion $region
     *
     * @return \Generated\Shared\Transfer\RegionTransfer
     */
    public function mapRegionTransfer(SpyRegion $region): RegionTransfer
    {
        $regionTransfer = (new RegionTransfer())
            ->fromArray($region->toArray(), true);

        return $regionTransfer;
    }
}
