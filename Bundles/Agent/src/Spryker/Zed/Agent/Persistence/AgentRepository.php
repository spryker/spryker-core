<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Persistence;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Agent\Persistence\AgentPersistenceFactory getFactory()
 */
class AgentRepository extends AbstractRepository implements AgentRepositoryInterface
{
    public function findAgentByUsername(string $username): ?UserTransfer
    {
        $userEntity = $this->getFactory()
            ->getUserQuery()
            ->filterByIsAgent(true)
            ->filterByUsername($username)
            ->findOne();

        if ($userEntity === null) {
            return null;
        }

        $userTransfer = new UserTransfer();

        return $userTransfer->fromArray($userEntity->toArray(), true);
    }

    public function findCustomersByQuery(CustomerQueryTransfer $customerQueryTransfer): CustomerAutocompleteResponseTransfer
    {
        $query = $customerQueryTransfer->getQuery() ?? '';
        $queryPattern = $query . '%';
        $offset = $customerQueryTransfer->getOffset();
        $limit = $customerQueryTransfer->getLimit() ?? $this->getFactory()->getConfig()->getDefaultCustomerPaginationLimit();

        $customersQuery = $this->getFactory()
            ->getCustomerQuery()
            ->filterByEmail_Like($queryPattern)
            ->_or()
            ->filterByLastName_Like($queryPattern)
            ->_or()
            ->filterByFirstName_Like($queryPattern)
            ->_or()
            ->filterByCustomerReference($query)
            ->select([
                SpyCustomerTableMap::COL_ID_CUSTOMER,
                SpyCustomerTableMap::COL_CUSTOMER_REFERENCE,
                SpyCustomerTableMap::COL_FIRST_NAME,
                SpyCustomerTableMap::COL_LAST_NAME,
                SpyCustomerTableMap::COL_EMAIL,
            ])
            ->setIgnoreCase(true)
            ->orderBy(SpyCustomerTableMap::COL_ID_CUSTOMER);

        $page = $offset && $limit ? floor($offset / $limit + 1) : 1;
        $pager = $customersQuery->paginate($page, $limit);
        $customers = $pager->getResults()->getData();

        return $this->getFactory()->createAgentMapper()
            ->mapCustomerDataToCustomerAutocompleteResponseTransfer($customers, $pager);
    }
}
