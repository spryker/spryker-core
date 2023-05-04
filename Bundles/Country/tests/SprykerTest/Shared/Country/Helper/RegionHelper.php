<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Country\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\RegionBuilder;
use Generated\Shared\Transfer\RegionTransfer;
use Orm\Zed\Country\Persistence\SpyRegionQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class RegionHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\RegionTransfer
     */
    public function haveRegion(array $seed = []): RegionTransfer
    {
        $regionTransfer = (new RegionBuilder($seed))->build();

        $regionEntity = SpyRegionQuery::create()
            ->filterByIso2Code($regionTransfer->getIso2Code())
            ->findOneOrCreate();
        $regionEntity->fromArray($regionTransfer->modifiedToArray());
        $regionEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($regionEntity): void {
            $this->deleteRegion($regionEntity->getIdRegion());
        });

        return $regionTransfer->fromArray($regionEntity->toArray(), true);
    }

    /**
     * @param int $idRegion
     *
     * @return void
     */
    protected function deleteRegion(int $idRegion): void
    {
        $regionEntity = $this->getRegionQuery()->findOneByIdRegion($idRegion);

        if ($regionEntity) {
            $regionEntity->delete();
        }
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    protected function getRegionQuery(): SpyRegionQuery
    {
        return SpyRegionQuery::create();
    }
}
