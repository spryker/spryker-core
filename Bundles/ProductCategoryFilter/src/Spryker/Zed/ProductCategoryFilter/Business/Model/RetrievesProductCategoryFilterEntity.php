<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface;

trait RetrievesProductCategoryFilterEntity
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface
     */
    protected $productCategoryFilterQueryContainer;

    /**
     * @param \Spryker\Zed\ProductCategoryFilter\Persistence\Propel\AbstractSpyProductCategoryFilterQuery $productCategoryFilterQueryContainer
     */
    public function __construct(ProductCategoryFilterQueryContainerInterface $productCategoryFilterQueryContainer)
    {
        $this->productCategoryFilterQueryContainer = $productCategoryFilterQueryContainer;
    }

    /**
     * @param int $categoryId
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilter
     */
    protected function getProductCategoryFilterEntityByCategoryId($categoryId)
    {
        return $this->productCategoryFilterQueryContainer
            ->queryProductCategoryFilterByCategoryId($categoryId)
            ->findOne();
    }
}
