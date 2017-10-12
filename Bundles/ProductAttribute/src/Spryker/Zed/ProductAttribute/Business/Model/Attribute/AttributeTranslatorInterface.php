<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Attribute;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;

interface AttributeTranslatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return void
     */
    public function saveProductManagementAttributeTranslation(ProductManagementAttributeTransfer $productManagementAttributeTransfer);
}
