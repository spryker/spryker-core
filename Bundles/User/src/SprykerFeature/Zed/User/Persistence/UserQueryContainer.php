<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\User\Persistence\Propel\SpyUserQuery;

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
        $query->filterByStatus(['active', 'blocked']);

        return $query;
    }

    /**
     * @return SpyUserQuery
     */
    public function queryUsersAndGroup()
    {
        $query = $this->getDependencyContainer()->createUserQuery();

        /*
         * @todo this is the query that should be used
         */
        $sql = '
        SELECT u.id_user, u.username, GROUP_CONCAT(g.name)
        FROM spy_user u
        LEFT JOIN spy_acl_user_has_group AS h ON (u.id_user=h.fk_user_user)
        LEFT JOIN spy_acl_group AS g ON (g.id_acl_group = h.fk_acl_group);
        ';

        return $query;
    }

}
