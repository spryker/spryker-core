<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesRestApi\Plugin\ProductsRestApi;

use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductsRestApiExtension\Dependency\Plugin\ConcreteProductsResourceExpanderPluginInterface;

/**
 * @method \Spryker\Glue\ProductAttributesRestApi\ProductAttributesRestApiFactory getFactory()
 */
class MultiSelectAttributeConcreteProductsResourceExpanderPlugin extends AbstractPlugin implements ConcreteProductsResourceExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Formats concrete-products resource "multiselect" attributes to string.
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
            ->createMultiSelectAttributeFormatter()
            ->formatConcreteMultiSelectAttributesToString($concreteProductsRestAttributesTransfer);
    }
}
