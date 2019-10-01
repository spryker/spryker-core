<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorageExtension\Dependency\Plugin;

interface ProductAbstractRestrictionFilterPluginInterface
{
    /**
     * Specification:
     * - Filters provided abstract product ids and return array of product ids that are not restricted.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function filter(array $productAbstractIds): array;
}
