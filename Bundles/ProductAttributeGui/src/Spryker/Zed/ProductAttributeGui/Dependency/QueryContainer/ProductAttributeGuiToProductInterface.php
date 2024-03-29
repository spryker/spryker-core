<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;

interface ProductAttributeGuiToProductInterface
{
    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract(): SpyProductAbstractQuery;

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProduct(): SpyProductQuery;
}
