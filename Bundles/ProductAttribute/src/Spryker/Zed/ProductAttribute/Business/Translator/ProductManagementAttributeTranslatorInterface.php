<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Translator;

use ArrayObject;

interface ProductManagementAttributeTranslatorInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $productManagementAttributeTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function translateProductManagementAttributes(ArrayObject $productManagementAttributeTransfers): ArrayObject;
}
