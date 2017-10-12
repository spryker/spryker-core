<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationCollector\Dependency\QueryContainer;

interface ProductRelationCollectorToProductImageInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryImageSetByProductAbstractId($idProductAbstract);
}
