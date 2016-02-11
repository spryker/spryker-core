<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Persistence;

interface AuthQueryContainerInterface
{

    /**
     * @return \Orm\Zed\Auth\Persistence\Base\SpyResetPasswordQuery
     */
    public function queryResetPassword();

    /**
     * @param string $code
     *
     * @return \Orm\Zed\Auth\Persistence\Base\SpyResetPasswordQuery
     */
    public function queryForActiveCode($code);

}
