<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence\Mapper;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch;

interface ProductPageSearchMapperInterface
{
    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch $productConcretePageSearchEntity
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer
     */
    public function mapProductConcretePageSearchEntityToTransfer(
        SpyProductConcretePageSearch $productConcretePageSearchEntity,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
    ): ProductConcretePageSearchTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch $productConcretePageSearchEntity
     *
     * @return \Orm\Zed\ProductPageSearch\Persistence\SpyProductConcretePageSearch
     */
    public function mapProductConcretePageSearchTransferToEntity(
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer,
        SpyProductConcretePageSearch $productConcretePageSearchEntity
    ): SpyProductConcretePageSearch;
}
