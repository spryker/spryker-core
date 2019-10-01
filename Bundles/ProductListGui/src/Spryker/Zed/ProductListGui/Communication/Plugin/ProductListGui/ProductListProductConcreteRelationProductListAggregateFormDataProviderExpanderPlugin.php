<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Plugin\ProductListGui;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListAggregateFormDataProviderExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class ProductListProductConcreteRelationProductListAggregateFormDataProviderExpanderPlugin extends AbstractPlugin implements ProductListAggregateFormDataProviderExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $options
     *
     * @return array
     */
    public function expandOptions(array $options): array
    {
        return $options;
    }

    /**
     * {@inheritdoc}
     * - Expands ProductListAggregateFormTransfer::assignedProductIds with data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListAggregateFormTransfer $productListAggregateFormTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListAggregateFormTransfer
     */
    public function expandData(ProductListAggregateFormTransfer $productListAggregateFormTransfer): ProductListAggregateFormTransfer
    {
        $productListProductConcreteRelationTransfer = $productListAggregateFormTransfer->getProductList()
            ->getProductListProductConcreteRelation() ?: new ProductListProductConcreteRelationTransfer();

        return $productListAggregateFormTransfer->setAssignedProductIds(
            implode(',', $productListProductConcreteRelationTransfer->getProductIds())
        );
    }
}
