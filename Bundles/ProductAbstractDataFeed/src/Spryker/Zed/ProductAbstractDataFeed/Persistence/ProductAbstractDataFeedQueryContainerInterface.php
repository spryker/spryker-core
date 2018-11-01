<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAbstractDataFeed\Persistence;

use Generated\Shared\Transfer\ProductAbstractDataFeedTransfer;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductAbstractDataFeedQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer|null $productDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAbstractProductDataFeed(?ProductAbstractDataFeedTransfer $productDataFeedTransfer = null);
}
