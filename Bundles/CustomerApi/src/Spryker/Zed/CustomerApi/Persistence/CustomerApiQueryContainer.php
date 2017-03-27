<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Persistence;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
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
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryFind()
    {
        return $this->queryCustomer();
    }

}
