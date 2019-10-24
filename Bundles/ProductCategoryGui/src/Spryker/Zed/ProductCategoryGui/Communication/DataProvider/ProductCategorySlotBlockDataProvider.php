<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryGui\Communication\DataProvider;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Spryker\Zed\ProductCategoryGui\Communication\Form\ProductCategorySlotBlockConditionForm;
use Spryker\Zed\ProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface;
use Spryker\Zed\ProductCategoryGui\Dependency\Facade\ProductCategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductCategoryGui\Dependency\QueryContainer\ProductCategoryGuiToCategoryQueryContainerInterface;
use Spryker\Zed\ProductCategoryGui\Dependency\QueryContainer\ProductCategoryGuiToProductQueryContainerInterface;

class ProductCategorySlotBlockDataProvider implements ProductCategorySlotBlockDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryGui\Dependency\QueryContainer\ProductCategoryGuiToProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface
     */
    protected $productLabelFormatter;

    /**
     * @var \Spryker\Zed\ProductCategoryGui\Dependency\QueryContainer\ProductCategoryGuiToCategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var \Spryker\Zed\ProductCategoryGui\Dependency\Facade\ProductCategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductCategoryGui\Dependency\QueryContainer\ProductCategoryGuiToProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface $productLabelFormatter
     * @param \Spryker\Zed\ProductCategoryGui\Dependency\QueryContainer\ProductCategoryGuiToCategoryQueryContainerInterface $categoryQueryContainer
     * @param \Spryker\Zed\ProductCategoryGui\Dependency\Facade\ProductCategoryGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductCategoryGuiToProductQueryContainerInterface $productQueryContainer,
        ProductLabelFormatterInterface $productLabelFormatter,
        ProductCategoryGuiToCategoryQueryContainerInterface $categoryQueryContainer,
        ProductCategoryGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productLabelFormatter = $productLabelFormatter;
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => CmsSlotBlockTransfer::class,
            ProductCategorySlotBlockConditionForm::OPTION_PRODUCT_ARRAY => $this->getProducts(),
            ProductCategorySlotBlockConditionForm::OPTION_CATEGORY_ARRAY => $this->getCategories(),
        ];
    }

    /**
     * @return array
     */
    protected function getProducts(): array
    {
        $idLocale = $this->getIdLocale();

        /** @var \Orm\Zed\Category\Persistence\SpyProductAbstract[] $productAbstractTransfers */
        $productAbstracts = $this->productQueryContainer
            ->queryProductAbstractWithName($idLocale)
            ->find();

        $products = [];

        foreach ($productAbstracts as $productAbstract) {
            $label = $this->productLabelFormatter->format(
                $productAbstract->getName(),
                $productAbstract->getSku()
            );
            $products[$label] = $productAbstract->getIdProductAbstract();
        }

        return $products;
    }

    /**
     * @return array
     */
    protected function getCategories(): array
    {
        $idLocale = $this->getIdLocale();

        /** @var \Orm\Zed\Category\Persistence\SpyCategory[] $categoryCollection */
        $categoryCollection = $this->categoryQueryContainer
            ->queryCategory($idLocale)
            ->find();

        $categories = [];

        foreach ($categoryCollection as $categoryEntity) {
            $categoryName = $categoryEntity->getLocalisedAttributes($idLocale)->getFirst()->getName();
            $categories[$categoryName] = $categoryEntity->getIdCategory();
        }

        return $categories;
    }

    /**
     * @return int|null
     */
    protected function getIdLocale(): ?int
    {
        return $this->localeFacade->getCurrentLocale()->getIdLocale();
    }
}
