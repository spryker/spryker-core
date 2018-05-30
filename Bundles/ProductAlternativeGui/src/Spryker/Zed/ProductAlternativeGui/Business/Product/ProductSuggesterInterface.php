<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business\Product;

interface ProductSuggesterInterface
{
    /**
     * @param string $searchName
     * @param null|int $limit
     *
     * @return string[]
     */
    public function suggestProduct(string $searchName, ?int $limit = null): array;
}
