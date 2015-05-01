<?php

namespace SprykerFeature\Zed\User\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\User\Persistence\Propel\SpyUserUserQuery;

/**
 * @method UserDependencyContainer getDependencyContainer()
 */
class UserQueryContainer extends AbstractQueryContainer
{
    /**
     * @param string $username
     *
     * @return SpyUserUserQuery
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
     * @return SpyUserUserQuery
     */
    public function queryUserById($id)
    {
        $query = $this->getDependencyContainer()->createUserQuery();
        $query->filterByIdUserUser($id);

        return $query;
    }

    /**
     * @return SpyUserUserQuery
     */
    public function queryUsers()
    {
        $query = $this->getDependencyContainer()->createUserQuery();
        $query->filterByStatus(['active', 'blocked']);

        return $query;
    }
}
