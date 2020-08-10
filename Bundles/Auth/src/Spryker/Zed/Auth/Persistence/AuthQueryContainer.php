<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Persistence;

use Orm\Zed\Auth\Persistence\Map\SpyResetPasswordTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Auth\Persistence\AuthPersistenceFactory getFactory()
 */
class AuthQueryContainer extends AbstractQueryContainer implements AuthQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Auth\Persistence\SpyResetPasswordQuery
     */
    public function queryResetPassword()
    {
        return $this->getFactory()->createResetPasswordQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $code
     *
     * @return \Orm\Zed\Auth\Persistence\SpyResetPasswordQuery
     */
    public function queryForActiveCode($code)
    {
        return $this->getFactory()->createResetPasswordQuery()
            ->filterByCode($code)
            ->filterByStatus(SpyResetPasswordTableMap::COL_STATUS_ACTIVE);
    }
}
