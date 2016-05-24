<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductVariantTransfer;

interface ProductVariantInterface
{
    /**
     * @param string $abstractSku
     *
     * @return ProductVariantTransfer[]
     */
    public function getProductVariantsByAbstractSku($abstractSku);
}
