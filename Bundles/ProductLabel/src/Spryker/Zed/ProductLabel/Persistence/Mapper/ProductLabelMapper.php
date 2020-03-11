<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence\Mapper;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;

class ProductLabelMapper
{
    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function mapProductLabelEntityToProductLabelTransfer(
        SpyProductLabel $productLabelEntity,
        ProductLabelTransfer $productLabelTransfer
    ): ProductLabelTransfer {
        return $productLabelTransfer->fromArray($productLabelEntity->toArray(), true);
    }
}
