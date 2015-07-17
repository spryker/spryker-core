<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Auth\Persistence\Propel\Base\SpyResetPasswordQuery;

class AuthQueryContainer extends AbstractQueryContainer
{

    /**
     * @return SpyResetPasswordQuery
     */
    public function queryResetPassword()
    {
        return SpyResetPasswordQuery::create();
    }

}
