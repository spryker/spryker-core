<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
