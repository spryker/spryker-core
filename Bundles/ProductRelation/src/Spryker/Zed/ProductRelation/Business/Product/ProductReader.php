<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Product;

use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;

class ProductReader implements ProductReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     */
    public function __construct(ProductRelationQueryContainerInterface $productRelationQueryContainer)
    {
        $this->productRelationQueryContainer = $productRelationQueryContainer;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array
     */
    public function getProductWithCategories(int $idProductAbstract, int $idLocale): array
    {
        return $this->productRelationQueryContainer
            ->queryProductsWithCategoriesByFkLocale($idLocale)
            ->filterByIdProductAbstract($idProductAbstract)
            ->findOne();
    }
}
