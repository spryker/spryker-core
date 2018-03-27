<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business\Model;

interface ProductQuantityReaderInterface
{
    /**
     * @param string[] $productSkus
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[]
     */
    public function getProductQuantityEntitiesByProductSku(array $productSkus);

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[]
     */
    public function getProductQuantityEntitiesByProductIds(array $productIds);
}
