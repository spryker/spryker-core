<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Persistence;

use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\User\Persistence\UserPersistenceFactory getFactory()
 */
class UserQueryContainer extends AbstractQueryContainer implements UserQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $username
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUserByUsername($username)
    {
        $query = $this->getFactory()->createUserQuery();
        $query->filterByUsername($username);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUserById($id)
    {
        $query = $this->getFactory()->createUserQuery();
        $query->filterByIdUser($id);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUsers()
    {
        $query = $this->getFactory()->createUserQuery();
        $query->filterByStatus([SpyUserTableMap::COL_STATUS_ACTIVE, SpyUserTableMap::COL_STATUS_BLOCKED]);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUser()
    {
        return $this->getFactory()->createUserQuery();
    }
}
