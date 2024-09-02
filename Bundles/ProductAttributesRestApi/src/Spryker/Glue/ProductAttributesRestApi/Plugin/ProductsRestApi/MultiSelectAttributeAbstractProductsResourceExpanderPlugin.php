<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesRestApi\Plugin\ProductsRestApi;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductsRestApiExtension\Dependency\Plugin\AbstractProductsResourceExpanderPluginInterface;

/**
 * @method \Spryker\Glue\ProductAttributesRestApi\ProductAttributesRestApiFactory getFactory()
 */
class MultiSelectAttributeAbstractProductsResourceExpanderPlugin extends AbstractPlugin implements AbstractProductsResourceExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Formats abstract-products resource "multiselect" attributes to string.
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
            ->createMultiSelectAttributeFormatter()
            ->formatAbstractMultiSelectAttributesToString($abstractProductsRestAttributesTransfer);
    }
}
