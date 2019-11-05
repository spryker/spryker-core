<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Reader;

interface AbstractProductsCategoriesReaderInterface
{
    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return int[]|null
     */
    public function findProductCategoryNodeIds(string $sku, string $localeName): ?array;

    /**
     * @param string[] $productAbstractSkus
     * @param string $localeName
     *
     * @return array
     */
    public function findProductCategoryNodeIdsBySkus(array $productAbstractSkus, string $localeName): array;
}
