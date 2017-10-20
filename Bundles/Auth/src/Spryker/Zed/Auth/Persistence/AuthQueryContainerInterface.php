<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Persistence;

interface AuthQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Auth\Persistence\SpyResetPasswordQuery
     */
    public function queryResetPassword();

    /**
     * @api
     *
     * @param string $code
     *
     * @return \Orm\Zed\Auth\Persistence\SpyResetPasswordQuery
     */
    public function queryForActiveCode($code);
}
