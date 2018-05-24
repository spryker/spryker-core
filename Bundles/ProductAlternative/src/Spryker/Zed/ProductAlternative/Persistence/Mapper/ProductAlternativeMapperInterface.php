<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative;

interface ProductAlternativeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     * @param \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative $spyProductAlternative
     *
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative
     */
    public function mapProductAlternativeTransferToEntity(
        ProductAlternativeTransfer $productAlternativeTransfer,
        SpyProductAlternative $spyProductAlternative
    ): SpyProductAlternative;

    /**
     * @param \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative $spyProductAlternative
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function mapEntityToProductAlternativeTransfer(
        SpyProductAlternative $spyProductAlternative,
        ProductAlternativeTransfer $productAlternativeTransfer
    ): ProductAlternativeTransfer;
}
