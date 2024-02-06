<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Orm\Zed\User\Persistence\SpyUserQuery;

class UserQueryCriteriaMapper
{
    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param \Orm\Zed\User\Persistence\SpyUserQuery $userQuery
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function mapQueryCriteriaTransferToUserQueryCriteria(
        QueryCriteriaTransfer $queryCriteriaTransfer,
        SpyUserQuery $userQuery
    ): SpyUserQuery {
        $userQuery = $this->addConditions($queryCriteriaTransfer, $userQuery);

        return $userQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param \Orm\Zed\User\Persistence\SpyUserQuery $userQuery
     *
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    protected function addConditions(
        QueryCriteriaTransfer $queryCriteriaTransfer,
        SpyUserQuery $userQuery
    ): SpyUserQuery {
        /** @phpstan-var literal-string $clause */
        foreach ($queryCriteriaTransfer->getConditions() as $clause => $value) {
            $userQuery->where($clause, $value);
        }

        return $userQuery;
    }
}
