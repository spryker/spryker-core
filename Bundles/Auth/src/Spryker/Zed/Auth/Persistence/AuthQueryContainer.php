<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Auth\Persistence\Map\SpyResetPasswordTableMap;

/**
 * @method AuthPersistenceFactory getFactory()
 */
class AuthQueryContainer extends AbstractQueryContainer
{

    /**
     * @return \Orm\Zed\Auth\Persistence\Base\SpyResetPasswordQuery
     */
    public function queryResetPassword()
    {
        return $this->getFactory()->createResetPasswordQuery();
    }

    /**
     * @param string $code
     *
     * @return \Orm\Zed\Auth\Persistence\Base\SpyResetPasswordQuery
     */
    public function queryForActiveCode($code)
    {
        return $this->getFactory()->createResetPasswordQuery()
            ->filterByCode($code)
            ->filterByStatus(SpyResetPasswordTableMap::COL_STATUS_ACTIVE);
    }

}
