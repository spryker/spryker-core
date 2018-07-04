<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListAggregateFormType;
use Spryker\Zed\ProductListGui\Persistence\ProductListGuiRepositoryInterface;

class ProductListAggregateFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListFormDataProvider
     */
    protected $productListFormDataProvider;

    /**
     * @var \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListCategoryRelationFormDataProvider
     */
    protected $productListCategoryRelationFormDataProvider;

    /**
     * @var \Spryker\Zed\ProductListGui\Persistence\ProductListGuiRepositoryInterface
     */
    protected $productListGuiRepository;

    /**
     * @var \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListOwnerTypeFormExpanderPluginInterface[]
     */
    protected $productListOwnerTypeFormExpanderPlugins;

    /**
     * ProductListAggregateFormDataProvider constructor.
     *
     * @param \Spryker\Zed\ProductListGui\Persistence\ProductListGuiRepositoryInterface $productListGuiRepository
     * @param \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListFormDataProvider $productListFormDataProvider
     * @param \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListCategoryRelationFormDataProvider $productListCategoryRelationFormDataProvider
     * @param \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListOwnerTypeFormExpanderPluginInterface[] $productListOwnerTypeFormExpanderPlugins
     */
    public function __construct(
        ProductListGuiRepositoryInterface $productListGuiRepository,
        ProductListFormDataProvider $productListFormDataProvider,
        ProductListCategoryRelationFormDataProvider $productListCategoryRelationFormDataProvider,
        array $productListOwnerTypeFormExpanderPlugins = []
    ) {
        $this->productListFormDataProvider = $productListFormDataProvider;
        $this->productListCategoryRelationFormDataProvider = $productListCategoryRelationFormDataProvider;
        $this->productListGuiRepository = $productListGuiRepository;
        $this->productListOwnerTypeFormExpanderPlugins = $productListOwnerTypeFormExpanderPlugins;
    }

    /**
     * @param int|null $idProductList
     *
     * @return \Generated\Shared\Transfer\ProductListAggregateFormTransfer
     */
    public function getData(?int $idProductList = null): ProductListAggregateFormTransfer
    {
        $assignedProductIds = [];
        $productListTransfer = $this->productListFormDataProvider->getData($idProductList);
        $productListCategoryRelation = $this->productListCategoryRelationFormDataProvider->getData($productListTransfer->getIdProductList());

        if ($productListTransfer->getProductListProductConcreteRelation()
            && $productListTransfer->getProductListProductConcreteRelation()->getProductIds()
        ) {
            foreach ($productListTransfer->getProductListProductConcreteRelation()->getProductIds() as $productId) {
                $assignedProductIds[] = $productId;
            }
        }

        $aggregateFormTransfer = new ProductListAggregateFormTransfer();
        $aggregateFormTransfer->setProductList($productListTransfer);
        $aggregateFormTransfer->setProductListCategoryRelation($productListCategoryRelation);
        $aggregateFormTransfer->setAssignedProductIds(implode(',', $assignedProductIds));

        return $aggregateFormTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return array_merge(
            $this->productListCategoryRelationFormDataProvider->getOptions(),
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
