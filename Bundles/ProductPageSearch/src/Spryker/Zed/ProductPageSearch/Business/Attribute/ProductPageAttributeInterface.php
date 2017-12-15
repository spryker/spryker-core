<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Attribute;

interface ProductPageAttributeInterface
{

    /**
     * @param $abstractAttributesData
     * @param $abstractLocalizedAttributesData
     * @param $concreteAttributesData
     * @param $concreteLocalizedAttributesData
     *
     * @return array
     */
    public function getCombinedProductAttributes($abstractAttributesData, $abstractLocalizedAttributesData, $concreteAttributesData, $concreteLocalizedAttributesData);

}
