<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAgent\Persistence;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;

interface MerchantAgentRepositoryInterface
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
    ): QueryCriteriaTransfer;
}
