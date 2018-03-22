<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Table\Helper;

use Orm\Zed\Product\Persistence\SpyProductAbstract;

interface ProductAbstractTableHelperInterface
{
    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    public function getProductPreview(SpyProductAbstract $productAbstractEntity);

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string|null
     */
    public function getProductPrice(SpyProductAbstract $productAbstractEntity);

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    public function getAbstractProductStatusLabel(SpyProductAbstract $productAbstractEntity);
}
