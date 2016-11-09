<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupPersistenceFactory getFactory()
 */
class CustomerGroupQueryContainer extends AbstractQueryContainer implements CustomerGroupQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    public function queryCustomerGroups()
    {
        return $this->getFactory()
            ->createSpyCustomerGroupQuery();
    }

}
