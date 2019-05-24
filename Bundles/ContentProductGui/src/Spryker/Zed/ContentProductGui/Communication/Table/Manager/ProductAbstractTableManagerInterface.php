<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Table\Manager;

use Orm\Zed\Product\Persistence\SpyProductAbstract;

interface ProductAbstractTableManagerInterface
{
    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string|null
     */
    public function getProductPreviewUrl(SpyProductAbstract $productAbstractEntity): ?string;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return bool
     */
    public function getAbstractProductStatus(SpyProductAbstract $productAbstractEntity): bool;
}
