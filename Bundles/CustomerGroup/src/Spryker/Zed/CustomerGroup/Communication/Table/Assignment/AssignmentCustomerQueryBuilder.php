<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Table\Assignment;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\CustomerGroup\Persistence\Map\SpyCustomerGroupToCustomerTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\CustomerGroup\Dependency\QueryContainer\CustomerGroupToCustomerQueryContainerInterface;

class AssignmentCustomerQueryBuilder implements AssignmentCustomerQueryBuilderInterface
{
    /**
     * @var \Spryker\Zed\CustomerGroup\Dependency\QueryContainer\CustomerGroupToCustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @param \Spryker\Zed\CustomerGroup\Dependency\QueryContainer\CustomerGroupToCustomerQueryContainerInterface $customerQueryContainer
     */
    public function __construct(CustomerGroupToCustomerQueryContainerInterface $customerQueryContainer)
    {
        $this->customerQueryContainer = $customerQueryContainer;
    }

    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function buildNotAssignedQuery($idCustomerGroup = null)
    {
        $query = $this->customerQueryContainer->queryCustomers();

        if ($idCustomerGroup) {
            $query->addJoin(
                [SpyCustomerTableMap::COL_ID_CUSTOMER, $idCustomerGroup],
                [SpyCustomerGroupToCustomerTableMap::COL_FK_CUSTOMER, SpyCustomerGroupToCustomerTableMap::COL_FK_CUSTOMER_GROUP],
                Criteria::LEFT_JOIN
            )
                ->addAnd(SpyCustomerGroupToCustomerTableMap::COL_FK_CUSTOMER_GROUP, null, Criteria::ISNULL);
        }

        return $query;
    }

    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function buildAssignedQuery($idCustomerGroup = null)
    {
        $query = $this->customerQueryContainer
                ->queryCustomers()
                ->useSpyCustomerGroupToCustomerQuery()
                    ->filterByFkCustomerGroup($idCustomerGroup)
                ->endUse();

        return $query;
    }
}
