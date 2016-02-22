<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Auth\Persistence\Map\SpyResetPasswordTableMap;

/**
 * @method \Spryker\Zed\Auth\Persistence\AuthPersistenceFactory getFactory()
 */
class AuthQueryContainer extends AbstractQueryContainer implements AuthQueryContainerInterface
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
