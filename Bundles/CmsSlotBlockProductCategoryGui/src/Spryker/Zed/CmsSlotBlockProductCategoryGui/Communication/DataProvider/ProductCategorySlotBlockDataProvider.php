<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\DataProvider;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\ProductCategorySlotBlockConditionForm;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\QueryContainer\CmsSlotBlockProductCategoryGuiToProductQueryContainerInterface;

class ProductCategorySlotBlockDataProvider implements ProductCategorySlotBlockDataProviderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\QueryContainer\CmsSlotBlockProductCategoryGuiToProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface
     */
    protected $productLabelFormatter;

    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\QueryContainer\CmsSlotBlockProductCategoryGuiToProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface $productLabelFormatter
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CmsSlotBlockProductCategoryGuiToProductQueryContainerInterface $productQueryContainer,
        ProductLabelFormatterInterface $productLabelFormatter,
        CmsSlotBlockProductCategoryGuiToCategoryFacadeInterface $categoryFacade,
        CmsSlotBlockProductCategoryGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productLabelFormatter = $productLabelFormatter;
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int[]|null $productAbstractIds
     *
     * @return array
     */
    public function getOptions(?array $productAbstractIds = []): array
    {
        return [
            ProductCategorySlotBlockConditionForm::OPTION_PRODUCT_ARRAY => $this->getProductAbstracts($productAbstractIds),
            ProductCategorySlotBlockConditionForm::OPTION_CATEGORY_ARRAY => $this->getCategories(),
        ];
    }

    /**
     * @param int[]|null $productAbstractIds
     *
     * @return int[]
     */
    protected function getProductAbstracts(?array $productAbstractIds = []): array
    {
        if (!$productAbstractIds) {
             return [];
        }

        $idLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();
        $productAbstractEntityCollection = $this->productQueryContainer
                ->queryProductAbstractWithName($idLocale)
                ->filterByIdProductAbstract_In($productAbstractIds)
                ->find();

        return $this->getProductAbstractIdsFromCollection($productAbstractEntityCollection);
    }

    /**
     * @return int[]
     */
    protected function getCategories(): array
    {
        $categoryCollectionTransfer = $this->categoryFacade
            ->getAllCategoryCollection($this->localeFacade->getCurrentLocale());

        return $this->getCategoryIdsFromCollection($categoryCollectionTransfer);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Product\Persistence\SpyProductAbstract[] $productAbstractEntityCollection
     *
     * @return array
     */
    protected function getProductAbstractIdsFromCollection(ObjectCollection $productAbstractEntityCollection): array
    {
        $productIds = [];

        foreach ($productAbstractEntityCollection as $productAbstract) {
            $label = $this->productLabelFormatter->format(
                $productAbstract->getName(),
                $productAbstract->getSku()
            );
            $productIds[$label] = $productAbstract->getIdProductAbstract();
        }

        return $productIds;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return int[]
     */
    protected function getCategoryIdsFromCollection(CategoryCollectionTransfer $categoryCollectionTransfer): array
    {
        $categoryIds = [];

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $categoryIds[$categoryTransfer->getName()] = $categoryTransfer->getIdCategory();
        }

        return $categoryIds;
    }
}
