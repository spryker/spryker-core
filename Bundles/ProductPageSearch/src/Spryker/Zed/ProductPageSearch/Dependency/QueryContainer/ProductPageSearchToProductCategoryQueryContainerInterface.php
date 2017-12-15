<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\QueryContainer;

interface ProductPageSearchToProductCategoryQueryContainerInterface
{

    /**
     * @param int $idProductAbstract
     * @param array $idsCategoryNode
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingsByIdAbstractProductAndIdsCategoryNode($idProductAbstract, array $idsCategoryNode);

    /**
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryMappings();

}
