<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Plugin\ProductListGui;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListAggregateFormDataProviderExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class ProductListCategoryRelationProductListAggregateFormDataProviderExpanderPlugin extends AbstractPlugin implements ProductListAggregateFormDataProviderExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands form options with category IDs.
     *
     * @api
     *
     * @param array $options
     *
     * @return array
     */
    public function expandOptions(array $options): array
    {
        return $productListCategoryRelationTransfer = $this->getFactory()
            ->createProductListCategoryRelationFormDataProvider()
            ->getOptions();
    }

    /**
     * {@inheritdoc}
     * - Expands ProductListAggregateFormTransfer::productListCategoryRelation with data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListAggregateFormTransfer $productListAggregateFormTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListAggregateFormTransfer
     */
    public function expandData(ProductListAggregateFormTransfer $productListAggregateFormTransfer): ProductListAggregateFormTransfer
    {
        $productListCategoryRelationTransfer = $this->getFactory()
            ->createProductListCategoryRelationFormDataProvider()
            ->getData($productListAggregateFormTransfer->getProductList()->getIdProductList());

        return $productListAggregateFormTransfer->setProductListCategoryRelation($productListCategoryRelationTransfer);
    }
}
