<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAgent\Communication\Plugin\User;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserQueryCriteriaExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantAgent\Persistence\MerchantAgentRepositoryInterface getRepository()
 */
class MerchantAgentUserQueryCriteriaExpanderPlugin extends AbstractPlugin implements UserQueryCriteriaExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Uses `UserCriteriaTransfer.userConditions.isMerchantAgent` to expand the user's table query criteria with the `isMerchantAgent` condition.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expand(QueryCriteriaTransfer $queryCriteriaTransfer, UserCriteriaTransfer $userCriteriaTransfer): QueryCriteriaTransfer
    {
        return $this->getRepository()->expandUserQueryCriteria(
            $queryCriteriaTransfer,
            $userCriteriaTransfer,
        );
    }
}
