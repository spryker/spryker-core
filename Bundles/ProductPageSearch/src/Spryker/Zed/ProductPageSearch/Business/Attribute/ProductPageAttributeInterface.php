<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Attribute;

interface ProductPageAttributeInterface
{
    /**
     * @param string $abstractAttributesData
     * @param string $abstractLocalizedAttributesData
     * @param string $concreteAttributesData
     * @param string $concreteLocalizedAttributesData
     *
     * @return array
     */
    public function getCombinedProductAttributes(
        $abstractAttributesData,
        $abstractLocalizedAttributesData,
        $concreteAttributesData,
        $concreteLocalizedAttributesData
    );
}
