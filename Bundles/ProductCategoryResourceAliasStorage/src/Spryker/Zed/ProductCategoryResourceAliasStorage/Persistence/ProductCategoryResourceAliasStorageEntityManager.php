<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryResourceAliasStorage\Persistence;

use Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

class ProductCategoryResourceAliasStorageEntityManager extends AbstractEntityManager implements ProductCategoryResourceAliasStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\ProductCategoryStorage\Persistence\SpyProductAbstractCategoryStorage $spyProductAbstractCategoryStorage
     *
     * @return void
     */
    public function saveProductAbstractCategoryStorageEntity(SpyProductAbstractCategoryStorage $spyProductAbstractCategoryStorage): void
    {
        $spyProductAbstractCategoryStorage->save();
    }
}
