<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Filter;

interface SingleValueSuperAttributeFilterInterface
{
    /**
     * @param array<string[]> $productConcreteSuperAttributeMap
     * @param array<string[]> $superAttributeVariations
     *
     * @return array<string[]>
     */
    public function filterOutSingleValueSuperAttributes(
        array $productConcreteSuperAttributeMap,
        array $superAttributeVariations
    ): array;
}
