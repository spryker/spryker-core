<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordReset\Persistence;

use Orm\Zed\UserPasswordReset\Persistence\SpyResetPasswordQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\UserPasswordReset\Persistence\Propel\Mapper\ResetPasswordMapper;

/**
 * @method \Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetRepositoryInterface getRepository()
 * @method \Spryker\Zed\UserPasswordReset\UserPasswordResetConfig getConfig()
 */
class UserPasswordResetPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\UserPasswordReset\Persistence\Propel\Mapper\ResetPasswordMapper
     */
    public function createPropelResetPasswordMapper(): ResetPasswordMapper
    {
        return new ResetPasswordMapper();
    }

    /**
     * @return \Orm\Zed\UserPasswordReset\Persistence\SpyResetPasswordQuery
     */
    public function createPropelResetPasswordQuery(): SpyResetPasswordQuery
    {
        return SpyResetPasswordQuery::create();
    }
}
