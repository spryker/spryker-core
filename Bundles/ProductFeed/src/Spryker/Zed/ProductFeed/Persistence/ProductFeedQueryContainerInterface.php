<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductFeed\Persistence;

use Generated\Shared\Transfer\ProductFeedConditionTransfer;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductFeedQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @param ProductFeedConditionTransfer $productFeedConditionTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductFeedCollection(ProductFeedConditionTransfer $productFeedConditionTransfer);

}
