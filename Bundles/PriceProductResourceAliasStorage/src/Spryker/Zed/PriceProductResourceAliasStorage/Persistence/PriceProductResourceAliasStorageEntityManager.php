<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Persistence;

use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

class PriceProductResourceAliasStorageEntityManager extends AbstractEntityManager implements PriceProductResourceAliasStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage $priceProductAbstractStorage
     *
     * @return void
     */
    public function savePriceProductAbstractStorageEntity(SpyPriceProductAbstractStorage $priceProductAbstractStorage): void
    {
        $priceProductAbstractStorage->save();
    }

    /**
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage $priceProductConcreteStorage
     *
     * @return void
     */
    public function savePriceProductConcreteStorageEntity(SpyPriceProductConcreteStorage $priceProductConcreteStorage): void
    {
        $priceProductConcreteStorage->save();
    }
}
