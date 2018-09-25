<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryPersistenceFactory getFactory()
 */
class ProductCategoryRepository extends AbstractRepository implements ProductCategoryRepositoryInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findCategoryIdsByIdProductAbstract(int $idProductAbstract): array
    {
        $spyProductCategoryCollection = $this->queryCategoriesByIdProductAbstract($idProductAbstract)->find();

        return $this->getFactory()->createCategoryMapper()->getIdsCategoryList($spyProductCategoryCollection);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    protected function queryCategoriesByIdProductAbstract(int $idProductAbstract): SpyProductCategoryQuery
    {
        return $this->getFactory()->createProductCategoryQuery()
            ->addAnd(
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT,
                $idProductAbstract,
                Criteria::EQUAL
            );
    }
}
