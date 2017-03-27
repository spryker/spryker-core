<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Persistence;

use Generated\Shared\Transfer\ApiRequestTransfer;
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
     * @param ApiRequestTransfer $apiFilterTransfer
     *
     * @return array
     */
    public function find(ApiRequestTransfer $apiFilterTransfer)
    {
        $query = $this->queryCustomer();


    }

}
