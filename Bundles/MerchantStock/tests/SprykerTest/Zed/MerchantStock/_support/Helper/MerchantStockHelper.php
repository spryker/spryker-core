<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantStock\Helper;

use Codeception\Module;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;

class MerchantStockHelper extends Module
{
    /**
     * @param int $idMerchant
     * @param int $idStock
     *
     * @return \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock
     */
    public function haveMerchantStock(int $idMerchant, int $idStock): SpyMerchantStock
    {
        $merchantStockEntity = (new SpyMerchantStock())
            ->setFkMerchant($idMerchant)
            ->setFkStock($idStock);

        $merchantStockEntity->save();

        return $merchantStockEntity;
    }
}
