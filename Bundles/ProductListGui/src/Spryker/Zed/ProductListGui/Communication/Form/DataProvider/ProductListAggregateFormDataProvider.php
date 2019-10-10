<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Spryker\Zed\ProductListGui\Communication\Expander\ProductListAggregateFormDataProviderExpanderInterface;

class ProductListAggregateFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListFormDataProvider
     */
    protected $productListFormDataProvider;

    /**
     * @var \Spryker\Zed\ProductListGui\Communication\Expander\ProductListAggregateFormDataProviderExpanderInterface
     */
    protected $productListAggregateFormDataProviderExpander;

    /**
     * @param \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListFormDataProvider $productListFormDataProvider
     * @param \Spryker\Zed\ProductListGui\Communication\Expander\ProductListAggregateFormDataProviderExpanderInterface $productListAggregateFormDataProviderExpander
     */
    public function __construct(
        ProductListFormDataProvider $productListFormDataProvider,
        ProductListAggregateFormDataProviderExpanderInterface $productListAggregateFormDataProviderExpander
    ) {
        $this->productListFormDataProvider = $productListFormDataProvider;
        $this->productListAggregateFormDataProviderExpander = $productListAggregateFormDataProviderExpander;
    }

    /**
     * @param int|null $idProductList
     *
     * @return \Generated\Shared\Transfer\ProductListAggregateFormTransfer
     */
    public function getData(?int $idProductList = null): ProductListAggregateFormTransfer
    {
        $productListTransfer = $this->productListFormDataProvider->getData($idProductList);

        $productListAggregateFormTransfer = (new ProductListAggregateFormTransfer())->setProductList($productListTransfer);
        $productListAggregateFormTransfer = $this->productListAggregateFormDataProviderExpander
            ->expandProductListAggregateFormData($productListAggregateFormTransfer);

        return $productListAggregateFormTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $options = [];

        return $this->productListAggregateFormDataProviderExpander->expandOptions($options);
    }
}
