<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProduct\Helper;

use Codeception\Module;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class MerchantProductHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param int $idMerchant
     * @param int $idProductAbstract
     *
     * @return int
     */
    public function addMerchantProductRelation(int $idMerchant, int $idProductAbstract): int
    {
        $merchantProductAbstract = $this->getMerchantProductAbstractPropelQuery()
            ->filterByFkMerchant($idMerchant)
            ->filterByFkProductAbstract($idProductAbstract)
            ->findOneOrCreate();

        $merchantProductAbstract->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantProductAbstract) {
            $merchantProductAbstract->delete();
        });

        return $merchantProductAbstract->getIdMerchantProductAbstract();
    }

    /**
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    protected function getMerchantProductAbstractPropelQuery(): SpyMerchantProductAbstractQuery
    {
        return SpyMerchantProductAbstractQuery::create();
    }
}
