<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Generator;

interface AttributeVariantMapGeneratorInterface
{
    /**
     * @param array $productAttributeMapByIdProduct
     *
     * @return array
     */
    public function generateAttributeVariantMap(array $productAttributeMapByIdProduct): array;
}
