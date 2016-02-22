<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Transformer;

/**
 * Interface ProductAttributesTransformerInterface
 */

interface ProductAttributesTransformerInterface
{

    /**
     * @param array $productsRaw
     * @param array $searchableProducts
     *
     * @return array
     */
    public function buildProductAttributes(array $productsRaw, array $searchableProducts);

}
