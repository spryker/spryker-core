<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDataFeed\Persistence;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Generated\Shared\Transfer\ProductDataFeedTransfer;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductDataFeedQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @param ProductDataFeedTransfer $productDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     *
     */
    public function getProductDataFeedQuery(ProductDataFeedTransfer $productDataFeedTransfer);

}
