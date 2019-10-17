<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;

interface AbstractProductsResourceExpanderPluginInterface
{
    /**
     * Specification:
     *  - Expands abstract-products resource with additional data.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    public function expand(
        int $idProductAbstract,
        AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
    ): AbstractProductsRestAttributesTransfer;
}
