<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Transfer;

use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute;

interface ProductAttributeTransferMapperInterface
{
    /**
     * @param \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute $productAttributeEntity
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    public function convertProductAttribute(SpyProductSearchAttribute $productAttributeEntity);
}
