<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Persistence;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
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
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomer()
    {
        return $this->getFactory()->createCustomerQuery();
    }

    /**
     * @api
     *
     * @param array $fields
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryFind(array $fields = [])
    {
        $query = $this->mapQueryFields($this->queryCustomer(), $fields);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idCustomer
     * @param array $fields
     *
     * @return null|\Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerById($idCustomer, array $fields = [])
    {
        $query = $this->mapQueryFields($this->queryCustomer(), $fields);

        return $query->filterByIdCustomer($idCustomer);
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomerQuery $queryCustomer
     * @param array $fields
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|\Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    protected function mapQueryFields(SpyCustomerQuery $queryCustomer, array $fields)
    {
        return $this->getFactory()->getApiQueryContainer()->mapFields(
            SpyCustomerTableMap::TABLE_NAME,
            SpyCustomerTableMap::getFieldNames(TableMap::TYPE_FIELDNAME),
            $queryCustomer,
            $fields
        );
    }

}
