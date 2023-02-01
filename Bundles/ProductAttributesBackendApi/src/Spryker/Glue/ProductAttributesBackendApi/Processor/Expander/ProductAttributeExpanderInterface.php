<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Expander;

use ArrayObject;

interface ProductAttributeExpanderInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer> $productManagementAttributeValueTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer>
     */
    public function expandProductManagementAttributeValueTransfersWithLocaleName(
        ArrayObject $productManagementAttributeValueTransfers
    ): ArrayObject;
}
