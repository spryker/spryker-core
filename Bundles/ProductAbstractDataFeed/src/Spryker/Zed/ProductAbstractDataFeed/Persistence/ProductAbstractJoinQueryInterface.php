<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAbstractDataFeed\Persistence;

use Generated\Shared\Transfer\ProductAbstractDataFeedTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;

interface ProductAbstractJoinQueryInterface
{
    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function applyJoins(SpyProductAbstractQuery $abstractProductQuery, ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer);
}
