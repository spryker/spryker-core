<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AbstractProductDataFeed\Persistence;

use Generated\Shared\Transfer\AbstractProductDataFeedTransfer;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface AbstractProductDataFeedQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @param AbstractProductDataFeedTransfer $productDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAbstractProductDataFeed(AbstractProductDataFeedTransfer $productDataFeedTransfer);

}
