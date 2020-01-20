<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Model;

interface AclConverterInterface
{
    /**
     * @param array $roles
     *
     * @return \Generated\Shared\Transfer\RoleTransfer[]
     */
    public function convertRoleArrayToTransfers(array $roles): array;

    /**
     * @param array $rules
     *
     * @return \Generated\Shared\Transfer\RuleTransfer[]
     */
    public function convertRuleArrayToTransfers(array $rules): array;

    /**
     * @param array $groups
     *
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    public function convertGroupArrayToTransfers(array $groups): array;

    /**
     * @param array $userGroups
     *
     * @return \Generated\Shared\Transfer\UserGroupTransfer[]
     */
    public function convertUserGroupArrayToTransfers(array $userGroups): array;
}
