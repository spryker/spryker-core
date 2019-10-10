<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Plugin\ProductsRestApi;

use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductsRestApiExtension\Dependency\Plugin\ConcreteProductsResourceExpanderPluginInterface;

/**
 * @method \Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiFactory getFactory()
 */
class ProductReviewsConcreteProductsResourceExpanderPlugin extends AbstractPlugin implements ConcreteProductsResourceExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Expands concrete-products resource with reviews data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
     * @param int $idProductConcrete
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer
     */
    public function expand(
        ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer,
        int $idProductConcrete,
        RestRequestInterface $restRequest
    ): ConcreteProductsRestAttributesTransfer {
        return $this->getFactory()
            ->createProductReviewsConcreteProductsResourceExpander()
            ->expand($concreteProductsRestAttributesTransfer);
    }
}
