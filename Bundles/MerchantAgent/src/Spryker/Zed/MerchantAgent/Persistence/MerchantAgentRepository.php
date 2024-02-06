<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAgent\Persistence;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;

class MerchantAgentRepository implements MerchantAgentRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandUserQueryCriteria(
        QueryCriteriaTransfer $queryCriteriaTransfer,
        UserCriteriaTransfer $userCriteriaTransfer
    ): QueryCriteriaTransfer {
        $userConditionsTransfer = $userCriteriaTransfer->getUserConditions();
        if (!$userConditionsTransfer) {
            return $queryCriteriaTransfer;
        }

        $queryConditions = $queryCriteriaTransfer->getConditions();
        if ($userConditionsTransfer->getIsMerchantAgent() !== null) {
            $clause = sprintf('%s = ?', SpyUserTableMap::COL_IS_MERCHANT_AGENT);
            $queryConditions[$clause] = $userConditionsTransfer->getIsMerchantAgentOrFail();
        }

        return $queryCriteriaTransfer->setConditions($queryConditions);
    }
}
