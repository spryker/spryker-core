<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordReset\Persistence;

use Generated\Shared\Transfer\ResetPasswordCriteriaTransfer;
use Generated\Shared\Transfer\ResetPasswordTransfer;
use Orm\Zed\UserPasswordReset\Persistence\SpyResetPasswordQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\UserPasswordReset\Persistence\UserPasswordResetPersistenceFactory getFactory()
 */
class UserPasswordResetRepository extends AbstractRepository implements UserPasswordResetRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResetPasswordCriteriaTransfer $resetPasswordCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ResetPasswordTransfer|null
     */
    public function findOne(ResetPasswordCriteriaTransfer $resetPasswordCriteriaTransfer): ?ResetPasswordTransfer
    {
        $resetPasswordQuery = $this->getFactory()->createPropelResetPasswordQuery();
        $resetPasswordEntity = $this->applyFilters($resetPasswordQuery, $resetPasswordCriteriaTransfer)->findOne();

        if (!$resetPasswordEntity) {
            return null;
        }

        return $this->getFactory()
            ->createPropelResetPasswordMapper()
            ->mapResetPasswordEntityToResetPasswordTransfer($resetPasswordEntity, new ResetPasswordTransfer());
    }

    /**
     * @param \Orm\Zed\UserPasswordReset\Persistence\SpyResetPasswordQuery $resetPasswordQuery
     * @param \Generated\Shared\Transfer\ResetPasswordCriteriaTransfer $resetPasswordCriteriaTransfer
     *
     * @return \Orm\Zed\UserPasswordReset\Persistence\SpyResetPasswordQuery
     */
    protected function applyFilters(
        SpyResetPasswordQuery $resetPasswordQuery,
        ResetPasswordCriteriaTransfer $resetPasswordCriteriaTransfer
    ): SpyResetPasswordQuery {
        if ($resetPasswordCriteriaTransfer->getCode()) {
            $resetPasswordQuery->filterByCode($resetPasswordCriteriaTransfer->getCode());
        }

        return $resetPasswordQuery;
    }
}
