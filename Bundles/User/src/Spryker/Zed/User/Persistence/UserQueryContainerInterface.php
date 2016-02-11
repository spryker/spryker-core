<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Persistence;

interface UserQueryContainerInterface
{

    /**
     * @param string $username
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUserByUsername($username);

    /**
     * @param int $id
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUserById($id);

    /**
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUsers();

    /**
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUser();

}
