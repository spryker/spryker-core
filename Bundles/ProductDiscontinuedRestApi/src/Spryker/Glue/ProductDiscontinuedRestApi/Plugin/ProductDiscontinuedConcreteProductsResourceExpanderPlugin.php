<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductDiscontinuedRestApi\Plugin;

use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductsRestApiExtension\Dependency\Plugin\ConcreteProductsResourceExpanderPluginInterface;

/**
 * @method \Spryker\Glue\ProductDiscontinuedRestApi\ProductDiscontinuedRestApiFactory getFactory()
 */
class ProductDiscontinuedConcreteProductsResourceExpanderPlugin extends AbstractPlugin implements ConcreteProductsResourceExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Expands concrete-products resource with discontinued data.
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
            ->createConcreteProductsResourceExpander()
            ->expand($concreteProductsRestAttributesTransfer, $restRequest->getMetadata()->getLocale());
    }
}
