<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Orm\Zed\User\Persistence\SpyUserQuery;

/**
 * @method UserDependencyContainer getDependencyContainer()
 */
class UserQueryContainer extends AbstractQueryContainer
{
    /**
     * @param string $username
     *
     * @return SpyUserQuery
     */
    public function queryUserByUsername($username)
    {
        $query = $this->getDependencyContainer()->createUserQuery();
        $query->filterByUsername($username);

        return $query;
    }

    /**
     * @param int $id
     *
     * @return SpyUserQuery
     */
    public function queryUserById($id)
    {
        $query = $this->getDependencyContainer()->createUserQuery();
        $query->filterByIdUser($id);

        return $query;
    }

    /**
     * @return SpyUserQuery
     */
    public function queryUsers()
    {
        $query = $this->getDependencyContainer()->createUserQuery();
        $query->filterByStatus([SpyUserTableMap::COL_STATUS_ACTIVE, SpyUserTableMap::COL_STATUS_BLOCKED]);

        return $query;
    }

    /**
     * @return SpyUserQuery
     */
    public function queryUser()
    {
        return $this->getDependencyContainer()->createUserQuery();
    }

}
