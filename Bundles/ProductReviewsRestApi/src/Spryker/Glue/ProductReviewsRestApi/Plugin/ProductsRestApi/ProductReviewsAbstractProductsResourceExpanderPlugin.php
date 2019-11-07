<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Plugin\ProductsRestApi;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductsRestApiExtension\Dependency\Plugin\AbstractProductsResourceExpanderPluginInterface;

/**
 * @method \Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiFactory getFactory()
 */
class ProductReviewsAbstractProductsResourceExpanderPlugin extends AbstractPlugin implements AbstractProductsResourceExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Expands abstract-products resource with reviews data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    public function expand(
        AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer,
        int $idProductAbstract,
        string $localeName
    ): AbstractProductsRestAttributesTransfer {
        return $this->getFactory()
            ->createProductReviewsAbstractProductsResourceExpander()
            ->expand($abstractProductsRestAttributesTransfer, $idProductAbstract);
    }
}
