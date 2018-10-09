<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
class AbstractProductPageSearchListener extends AbstractPlugin
{
    public const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function publish(array $productAbstractIds)
    {
        $this->getFacade()->publish($productAbstractIds);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function unpublish(array $productAbstractIds)
    {
        $this->getFacade()->unpublish($productAbstractIds);
    }

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    protected function getRelatedCategoryIds(array $categoryIds)
    {
        $relatedCategoryIds = [];
        foreach ($categoryIds as $categoryId) {
            $categoryNodes = $this->getFactory()->getCategoryFacade()->getAllNodesByIdCategory($categoryId);
            foreach ($categoryNodes as $categoryNode) {
                $result = $this->getQueryContainer()->queryAllCategoryIdsByNodeId($categoryNode->getIdCategoryNode())->find()->getData();
                $relatedCategoryIds = array_merge($relatedCategoryIds, $result);
            }
        }

        return array_unique($relatedCategoryIds);
    }
}
