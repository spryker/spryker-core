<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\ProductAttribute;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;

interface AbstractProductAttributeTranslationExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    public function addProductAttributeTranslation(
        AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer,
        string $localeName
    ): AbstractProductsRestAttributesTransfer;
}
