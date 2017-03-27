<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CustomerApi\Persistence\CustomerApiPersistenceFactory getFactory()
 */
class CustomerApiQueryContainer extends AbstractQueryContainer implements CustomerApiQueryContainerInterface
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
        $query = $this->queryCustomer();
        $fieldMapper = $this->createFieldMapper();

        return $fieldMapper->mapFields($query, $fields);
    }

    /**
     * @api
     *
     * @param int $idCustomer
     * @param array $fields
     *
     * @return null|\Orm\Zed\Customer\Persistence\SpyCustomer
     */
    public function queryCustomerById($idCustomer, array $fields = [])
    {
        $query = $this->queryCustomer();
        $fieldMapper = $this->createFieldMapper();

        $query = $fieldMapper->mapFields($query, $fields);

        return $query
            ->filterByIdCustomer($idCustomer)
            ->findOne();
    }

    /**
     * @api
     *
     * @return FieldMapper\FieldMapperInterface
     */
    public function createFieldMapper()
    {
        return $this->getFactory()->createFieldMapper();
    }

}
