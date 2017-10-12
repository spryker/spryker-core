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
    public function queryFind()
    {
        return $this->getFactory()->createCustomerQuery();
    }

    /**
     * @api
     *
     * @param int $idCustomer
     *
     * @return null|\Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryGet($idCustomer)
    {
        $query = $this->getFactory()->createCustomerQuery();

        return $query->filterByIdCustomer($idCustomer);
    }

    /**
     * @api
     *
     * @param int $idCustomer
     *
     * @return null|\Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryRemove($idCustomer)
    {
        $query = $this->getFactory()->createCustomerQuery();

        return $query->filterByIdCustomer($idCustomer);
    }
}
