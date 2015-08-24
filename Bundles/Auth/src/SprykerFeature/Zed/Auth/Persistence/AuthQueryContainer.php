<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Auth\Persistence\Propel\Base\SpyResetPasswordQuery;
use SprykerFeature\Zed\Auth\Persistence\Propel\Map\SpyResetPasswordTableMap;

class AuthQueryContainer extends AbstractQueryContainer
{
    /**
     * @return SpyResetPasswordQuery
     */
    public function queryResetPassword()
    {
        return SpyResetPasswordQuery::create();
    }

    /**
     * @param string $code
     *
     * @return SpyResetPasswordQuery
     */
    public function queryForActiveCode($code)
    {
        return SpyResetPasswordQuery::create()
            ->filterByCode($code)
            ->filterByStatus(SpyResetPasswordTableMap::COL_STATUS_ACTIVE);
    }

}
