<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Business\Attribute;

interface AttributeProviderInterface
{
    /**
     * @return string[]
     */
    public function getAllAttributeTypes();
}
