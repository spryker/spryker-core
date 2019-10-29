<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryGui\Communication\DataProvider;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Propel\Runtime\Collection\ObjectCollection;
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
            ProductCategorySlotBlockConditionForm::OPTION_PRODUCT_IDS => $this->getProductAbstractIds(),
            ProductCategorySlotBlockConditionForm::OPTION_CATEGORY_IDS => $this->getCategoryIds(),
        ];
    }

    /**
     * @return int[]
     */
    protected function getProductAbstractIds(): array
    {
        $idLocale = $this->getIdLocale();

        $productAbstractEntityCollection = $this->productQueryContainer
            ->queryProductAbstractWithName($idLocale)
            ->find();

        return $this->getProductAbstractIdsFromCollection($productAbstractEntityCollection);
    }

    /**
     * @return int[]
     */
    protected function getCategoryIds(): array
    {
        $idLocale = $this->getIdLocale();

        $categoryEntityCollection = $this->categoryQueryContainer
            ->queryCategory($idLocale)
            ->find();

        return $this->getCategoryIdsFromCollection($categoryEntityCollection, $idLocale);
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
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Category\Persistence\SpyCategory[] $categoryEntityCollection
     * @param int $idLocale
     *
     * @return array
     */
    protected function getCategoryIdsFromCollection(ObjectCollection $categoryEntityCollection, int $idLocale): array
    {
        $categoryIds = [];

        foreach ($categoryEntityCollection as $categoryEntity) {
            $categoryName = $categoryEntity->getLocalisedAttributes($idLocale)->getFirst()->getName();
            $categoryIds[$categoryName] = $categoryEntity->getIdCategory();
        }

        return $categoryIds;
    }

    /**
     * @return int|null
     */
    protected function getIdLocale(): ?int
    {
        return $this->localeFacade->getCurrentLocale()->getIdLocale();
    }
}
