<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface AbstractProductsResourceExpanderPluginInterface
{
    /**
     * Specification:
     *  - Expands abstract-products resource with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
     * @param int $idProductAbstract
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    public function expand(
        AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer,
        int $idProductAbstract,
        RestRequestInterface $restRequest
    ): AbstractProductsRestAttributesTransfer;
}
