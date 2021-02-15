<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Persistence;

interface UserQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $username
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUserByUsername($username);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUserById($id);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUsers();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function queryUser();
}
