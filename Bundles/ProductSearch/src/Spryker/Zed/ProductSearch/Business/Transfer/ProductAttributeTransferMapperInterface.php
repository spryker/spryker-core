<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Transfer;

interface ProductAttributeTransferMapperInterface
{
    /**
     * @param array<string, mixed> $productAttribute
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    public function convertProductAttribute(array $productAttribute);
}
