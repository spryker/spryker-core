<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListAggregateFormType;

class ProductListAggregateFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListFormDataProvider
     */
    protected $productListFormDataProvider;

    /**
     * @var \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListAggregateFormDataProviderExpanderPluginInterface[]
     */
    protected $productListAggregateFormDataProviderExpanderPlugins;

    /**
     * @var \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListOwnerTypeFormExpanderPluginInterface[]
     */
    protected $productListOwnerTypeFormExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListFormDataProvider $productListFormDataProvider
     * @param \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListOwnerTypeFormExpanderPluginInterface[] $productListOwnerTypeFormExpanderPlugins
     * @param \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListAggregateFormDataProviderExpanderPluginInterface[] $productListAggregateFormDataProviderExpanderPlugins
     */
    public function __construct(
        ProductListFormDataProvider $productListFormDataProvider,
        array $productListOwnerTypeFormExpanderPlugins,
        array $productListAggregateFormDataProviderExpanderPlugins
    ) {
        $this->productListFormDataProvider = $productListFormDataProvider;
        $this->productListOwnerTypeFormExpanderPlugins = $productListOwnerTypeFormExpanderPlugins;
        $this->productListAggregateFormDataProviderExpanderPlugins = $productListAggregateFormDataProviderExpanderPlugins;
    }

    /**
     * @param int|null $idProductList
     *
     * @return \Generated\Shared\Transfer\ProductListAggregateFormTransfer
     */
    public function getData(?int $idProductList = null): ProductListAggregateFormTransfer
    {
        $assignedProductIds = [];
        $productListAggregateFormTransfer = new ProductListAggregateFormTransfer();

        $productListTransfer = $this->productListFormDataProvider->getData($idProductList);
        $productListAggregateFormTransfer->setProductList($productListTransfer);

        foreach ($this->productListAggregateFormDataProviderExpanderPlugins as $productListAggregateFormDataProviderExpanderPlugin) {
            $productListAggregateFormTransfer = $productListAggregateFormDataProviderExpanderPlugin->expandData($productListAggregateFormTransfer);
        }

        return $productListAggregateFormTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $options = [];

        foreach ($this->productListAggregateFormDataProviderExpanderPlugins as $productListAggregateFormDataProviderExpanderPlugin) {
            $options = $productListAggregateFormDataProviderExpanderPlugin->expandOptions($options);
        }

        return array_merge(
            $options,
            $this->getOwnerTypeOptions()
        );
    }

    /**
     * @return array
     */
    protected function getOwnerTypeOptions(): array
    {
        $options = [];
        foreach ($this->productListOwnerTypeFormExpanderPlugins as $productListOwnerTypeFormExpanderPlugin) {
            $ownerType = $productListOwnerTypeFormExpanderPlugin->getOwnerType();
            $options[ProductListAggregateFormType::OPTION_OWNER_TYPE] = [
                $ownerType => $ownerType,
            ];
        }

        return $options;
    }
}
