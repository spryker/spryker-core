<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence;

use Generated\Shared\Transfer\AclEntityRuleCollectionTransfer;
use Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer;
use Generated\Shared\Transfer\RolesTransfer;

interface AclEntityRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function getAclEntityRulesByRoles(RolesTransfer $rolesTransfer): AclEntityRuleCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityRuleCollectionTransfer
     */
    public function getAclEntityRules(AclEntityRuleCriteriaTransfer $aclEntityRuleCriteriaTransfer): AclEntityRuleCollectionTransfer;
}
