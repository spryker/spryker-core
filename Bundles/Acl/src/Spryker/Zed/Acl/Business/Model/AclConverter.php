<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserGroupTransfer;
use Generated\Shared\Transfer\UserTransfer;

class AclConverter implements AclConverterInterface
{
    /**
     * @param array $roles
     *
     * @return \Generated\Shared\Transfer\RoleTransfer[]
     */
    public function convertRoleArrayToTransfers(array $roles): array
    {
        $result = [];
        foreach ($roles as $role) {
            $result[] = (new RoleTransfer())
                ->setName($role['name'])
                ->setGroup((new GroupTransfer())->setName($role['group']));
        }

        return $result;
    }

    /**
     * @param array $rules
     *
     * @return \Generated\Shared\Transfer\RuleTransfer[]
     */
    public function convertRuleArrayToTransfers(array $rules): array
    {
        $result = [];
        foreach ($rules as $rule) {
            $result[] = (new RuleTransfer())
                ->setType($rule['type'])
                ->setAction($rule['action'])
                ->setBundle($rule['bundle'])
                ->setController($rule['controller'])
                ->setRole((new RoleTransfer())->setName($rule['role']));
        }

        return $result;
    }

    /**
     * @param array $groups
     *
     * @return \Generated\Shared\Transfer\GroupTransfer[]
     */
    public function convertGroupArrayToTransfers(array $groups): array
    {
        $result = [];
        foreach ($groups as $group) {
            $result[] = (new GroupTransfer())->setName($group['name']);
        }

        return $result;
    }

    /**
     * @param array $userGroups
     *
     * @return \Generated\Shared\Transfer\UserGroupTransfer[]
     */
    public function convertUserGroupArrayToTransfers(array $userGroups): array
    {
        $result = [];
        foreach ($userGroups as $username => $userData) {
            $result[] = (new UserGroupTransfer())
                ->setUser((new UserTransfer())->setUsername($username))
                ->setGroup((new GroupTransfer())->setName($userData['group']));
        }

        return $result;
    }
}
