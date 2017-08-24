<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Transfer;

use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Propel\Runtime\Collection\ObjectCollection;

interface StockProductTransferMapperInterface
{

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockProduct $stockProductEntity
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer
     */
    public function convertStockProduct(SpyStockProduct $stockProductEntity);

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $stockProductEntityCollection
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function convertStockProductCollection(ObjectCollection $stockProductEntityCollection);

}
