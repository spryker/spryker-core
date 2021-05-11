<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategorySearch;

use Codeception\Actor;
use Generated\Shared\Transfer\CategoryMapTransfer;
use Generated\Shared\Transfer\IntegerSortMapTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\ProductCategorySearch\Persistence\ProductCategorySearchRepository;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ProductCategorySearch\Business\ProductCategorySearchFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductCategorySearchBusinessTester extends Actor
{
    use _generated\ProductCategorySearchBusinessTesterActions;

    /**
     * @return array
     */
    public function getProductData(): array
    {
        return [
            'category_node_ids' => [14, 8],
            'all_parent_category_ids' => [14, 1, 5, 8],
            'boosted_category_names' => [
                14 => 'Variant Showcase',
                8 => 'Tablets',
            ],
            'category_names' => [
                5 => 'Computer',
            ],
            'sorted_categories' => [
                14 => [
                    'product_order' => 36,
                    'all_node_parents' => [14, 1],
                ],
                8 => [
                    'product_order' => 13,
                    'all_node_parents' => [5, 8, 1],
                ],
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function getExpectedPageMapTransfer(): PageMapTransfer
    {
        $productData = $this->getProductData();

        return (new PageMapTransfer())
            ->setCategory((new CategoryMapTransfer())
                ->setAllParents($productData['all_parent_category_ids'])
                ->setDirectParents($productData['category_node_ids']))
            ->setFullText(['Computer'])
            ->setFullTextBoosted(['Variant Showcase', 'Tablets'])
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:14')
                ->setValue($productData['sorted_categories'][14]['product_order']))
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:1')
                ->setValue($productData['sorted_categories'][14]['product_order']))
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:8')
                ->setValue($productData['sorted_categories'][8]['product_order']))
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:5')
                ->setValue($productData['sorted_categories'][8]['product_order']))
            ->addIntegerSort((new IntegerSortMapTransfer())
                ->setName('category:1')
                ->setValue($productData['sorted_categories'][8]['product_order']));
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[][]
     */
    public function getMappedProductCategoriesByIdProductAbstractAndStore(array $productAbstractIds): array
    {
        return (new ProductCategorySearchRepository())
            ->getMappedProductCategoriesByIdProductAbstractAndStore($productAbstractIds);
    }
}
