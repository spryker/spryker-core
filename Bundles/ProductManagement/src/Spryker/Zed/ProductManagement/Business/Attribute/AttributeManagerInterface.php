<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

interface AttributeManagerInterface
{

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection();

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
     */
    public function getProductAttributeValueCollection();

}
