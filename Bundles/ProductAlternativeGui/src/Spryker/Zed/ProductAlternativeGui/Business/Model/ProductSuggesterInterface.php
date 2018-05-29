<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Business\Model;

interface ProductSuggesterInterface
{
    /**
     * @param string $productName
     * @param int $limit
     *
     * @return string[]
     */
    public function suggestProductNames(string $productName, int $limit = 10): array;

    /**
     * @param string $productSku
     * @param int $limit
     *
     * @return string[]
     */
    public function suggestProductSkus(string $productSku, int $limit = 10): array;
}
