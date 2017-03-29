<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductApi\Persistence\ProductApiPersistenceFactory getFactory()
 */
class ProductApiQueryContainer extends AbstractQueryContainer implements ProductApiQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract()
    {
        return $this->getFactory()->createProductAbstractQuery();
    }

    /**
     * @api
     *
     * @param array $fields
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryFind(array $fields = [])
    {
        $query = $this->mapQueryFields($this->queryProductAbstract(), $fields);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param array $fields
     *
     * @return null|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractById($idProductAbstract, array $fields = [])
    {
        $query = $this->mapQueryFields($this->queryProductAbstract(), $fields);

        return $query->filterByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $queryProductAbstract
     * @param array $fields
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function mapQueryFields(SpyProductAbstractQuery $queryProductAbstract, array $fields)
    {
        return $this->getFactory()->getApiQueryContainer()->mapFields(
            SpyProductAbstractTableMap::TABLE_NAME,
            SpyProductAbstractTableMap::getFieldNames(TableMap::TYPE_FIELDNAME),
            $queryProductAbstract,
            $fields
        );
    }

}
