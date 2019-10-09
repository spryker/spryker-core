<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Plugin;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    public function expand(
        AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer,
        int $idProductAbstract,
        RestRequestInterface $restRequest
    ): AbstractProductsRestAttributesTransfer {
        return $this->getFactory()
            ->createProductReviewsAbstractProductsResourceExpander()
            ->expand($abstractProductsRestAttributesTransfer);
    }
}
