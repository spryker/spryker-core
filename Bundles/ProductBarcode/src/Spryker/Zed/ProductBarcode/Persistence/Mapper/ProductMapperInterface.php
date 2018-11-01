<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Persistence\Mapper;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;

interface ProductMapperInterface
{
    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $spyProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapSpyProductToProductConcreteTransfer(SpyProduct $spyProduct): ProductConcreteTransfer;
}
