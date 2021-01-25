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
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryFind()
    {
        return $this->getFactory()->createCustomerQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery|null
     */
    public function queryGet($idCustomer)
    {
        $query = $this->getFactory()->createCustomerQuery();

        return $query->filterByIdCustomer($idCustomer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery|null
     */
    public function queryRemove($idCustomer)
    {
        $query = $this->getFactory()->createCustomerQuery();

        return $query->filterByIdCustomer($idCustomer);
    }
}
