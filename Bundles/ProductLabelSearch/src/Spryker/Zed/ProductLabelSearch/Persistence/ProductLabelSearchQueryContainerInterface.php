<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductLabelSearchQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productLabelIds
     *
     * @return \Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabelProductAbstractQuery
     */
    public function queryProductLabelByProductLabelIds(array $productLabelIds);
}
