<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Filter;

interface ProductAbstractAttributeMapRestrictionFilterInterface
{
    /**
     * @param array $productStorageData
     *
     * @return array
     */
    public function filterAbstractProductVariantsData(array $productStorageData): array;
}
