<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Formatter;

class SuggestionsFormatter implements SuggestionsFormatterInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiQueryContainerInterface
     */
    protected $productCategoryFilterGuiQueryContainer;

    /**
     * @var \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiQueryContainerInterface $productCategoryFilterGuiQueryContainer
     * @param \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductFacadeInterface $productFacade
     */
    public function __construct($productCategoryFilterGuiQueryContainer, $productFacade)
    {
        $this->productCategoryFilterGuiQueryContainer = $productCategoryFilterGuiQueryContainer;
        $this->productFacade = $productFacade;
    }

    /**
     * @param string[] $suggestions
     * @param int $idCategory
     *
     * @return string[]
     */
    public function formatCategorySuggestions($suggestions, $idCategory)
    {
        $productIds = $this->productCategoryFilterGuiQueryContainer
            ->queryProductAbstractsInCategory($idCategory)
            ->find()
            ->toArray();

        $productAttributes = [];
        $suggestionsWithNumbers = [];
        $combinedAbstractAttributeKeys = $this->productFacade->getCombinedAbstractAttributeKeysForProductIds($productIds);
        foreach ($combinedAbstractAttributeKeys as $productId => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($suggestions[$attribute])) {
                    continue;
                }

                if (!isset($productAttributes[$attribute])) {
                    $productAttributes[$attribute] = 0;
                }

                $productAttributes[$attribute]++;

                $suggestionsWithNumbers[$attribute] = $attribute . ' (' . $productAttributes[$attribute] . ')';
            }
        }

        return $suggestionsWithNumbers;
    }
}
