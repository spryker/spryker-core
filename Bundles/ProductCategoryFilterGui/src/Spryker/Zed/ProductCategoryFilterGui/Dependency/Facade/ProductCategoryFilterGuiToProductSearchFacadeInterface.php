<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade;

interface ProductCategoryFilterGuiToProductSearchFacadeInterface
{
    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array<string>
     */
    public function suggestProductSearchAttributeKeys($searchText = '', $limit = 10): array;

    /**
     * @return array<string>
     */
    public function getAllProductAttributeKeys(): array;
}
