<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Shared\Acl\AclConstants;
use Spryker\Zed\Acl\Business\AclFacade;
use Spryker\Zed\Acl\Business\Exception\EmptyEntityException;
use Spryker\Zed\Acl\Business\Exception\RoleNameEmptyException;
use Spryker\Zed\Acl\Business\Exception\RoleNameExistsException;
use Spryker\Zed\Acl\Business\Exception\RootNodeModificationException;
use Spryker\Zed\Acl\Business\Exception\RuleNotFoundException;
use Spryker\Zed\User\Business\UserFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Acl
 * @group Business
 * @group AclTest
 * Add your own group annotations below this line
 */
class AclTest extends Unit
{
    /**
     * @var \Spryker\Zed\Acl\Business\AclFacade
     */
    protected $facade;

    /**
     * @var \Spryker\Zed\User\Business\UserFacade
     */
    protected $userFacade;

    /**
     * @var \Generated\Shared\Transfer\RolesTransfer
     */
    protected $rolesTransfer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->rolesTransfer = new RolesTransfer();
        $this->facade = new AclFacade();
        $this->userFacade = new UserFacade();
    }

    /**
     * @return array
     */
    private function mockGroupData()
    {
        $data['name'] = sprintf('name-%s', rand(100, 999));

        return $data;
    }

    /**
     * @return array
     */
    private function mockRoleData()
    {
        $data['name'] = sprintf('name-%s', rand(100, 999));

        return $data;
    }

    /**
     * @param string $type
     * @param int $idRole
     *
     * @return array
     */
    private function mockRuleData($type, $idRole)
    {
        $data['bundle'] = sprintf('bundle-%s', rand(100, 999));
        $data['controller'] = sprintf('controller-%s', rand(100, 999));
        $data['action'] = sprintf('action-%s', rand(100, 999));
        $data['type'] = $type;
        $data['fkAclRole'] = $idRole;

        return $data;
    }

    /**
     * @return array
     */
    private function mockUserData()
    {
        $data['firstName'] = sprintf('firstName-%s', rand(100, 999));
        $data['lastName'] = sprintf('lastName-%s', rand(100, 999));
        $data['username'] = sprintf('username-%s', rand(100, 999));
        $data['password'] = sprintf('password-%s', rand(100, 999));

        return $data;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    private function mockAddUser(array $data)
    {
        return $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);
    }

    /**
     * @return void
     */
    public function testAddGroup()
    {
        $data = $this->mockGroupData();

        $transfer = $this->facade->addGroup($data['name'], $this->rolesTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\GroupTransfer', $transfer);
        $this->assertNotNull($transfer->getIdAclGroup());
        $this->assertEquals($data['name'], $transfer->getName());
    }

    /**
     * @return void
     */
    public function testUpdateGroup()
    {
        $groupData = $this->mockGroupData();
        $groupData2 = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name'], $this->rolesTransfer);

        $dto2 = clone $groupDto;
        $dto2->setName($groupData2['name']);
        $this->facade->updateGroup($dto2, $this->rolesTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\GroupTransfer', $dto2);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertNotEquals($groupData2['name'], $groupDto->getName());
        $this->assertEquals($groupData2['name'], $dto2->getName());
    }

    /**
     * @return void
     */
    public function testGetGroupById()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name'], $this->rolesTransfer);

        $id = $groupDto->getIdAclGroup();

        unset($groupDto);

        $groupDto = $this->facade->getGroup($id);

        $this->assertInstanceOf('\Generated\Shared\Transfer\GroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($id, $groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());
    }

    /**
     * @return void
     */
    public function testRemoveGroup()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name'], $this->rolesTransfer);

        $removed = $this->facade->removeGroup($groupDto->getIdAclGroup());
        $this->assertTrue($removed);

        try {
            $this->facade->getGroup($groupDto->getIdAclGroup());
        } catch (EmptyEntityException $e) {
            $this->assertInstanceOf('\Spryker\Zed\Acl\Business\Exception\EmptyEntityException', $e);
        }
    }

    /**
     * @return void
     */
    public function testAddRole()
    {
        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);
        $groupData = $this->mockGroupData();
        $this->facade->addGroup($groupData['name'], $this->rolesTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\RoleTransfer', $roleDto);
        $this->assertNotNull($roleDto->getIdAclRole());
        $this->assertEquals($roleData['name'], $roleDto->getName());
    }

    /**
     * @return void
     */
    public function testUpdatesRole()
    {
        $roleData = $this->mockRoleData();
        $roleTransfer = $this->facade->addRole($roleData['name']);
        $roleTransfer = $this->facade->updateRole($roleTransfer);

        $this->assertInstanceOf(RoleTransfer::class, $roleTransfer);
        $this->assertNotNull($roleTransfer->getIdAclRole());
        $this->assertSame($roleData['name'], $roleTransfer->getName());
    }

    /**
     * @return void
     */
    public function testRoleNameUniquenessCheck()
    {
        $roleData = $this->mockRoleData();
        $roleName = $roleData['name'];
        $this->facade->addRole($roleName);

        $this->expectException(RoleNameExistsException::class);
        $this->expectExceptionMessage(sprintf('Role with name "%s" already exists!', $roleName));

        $this->facade->addRole($roleName);
    }

    /**
     * @return void
     */
    public function testRoleNameShouldNotBeEmpty()
    {
        $this->expectException(RoleNameEmptyException::class);
        $this->expectExceptionMessage('Role name should not be empty!');

        $this->facade->addRole('');
    }

    /**
     * @return void
     */
    public function testRootRoleIsNotAllowedToEdit()
    {
        $roleTransfer = $this->facade->getRoleByName(AclConstants::ROOT_ROLE);

        $this->expectException(RootNodeModificationException::class);
        $this->expectExceptionMessage('Could not modify root role node!');

        $this->facade->updateRole($roleTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveRole()
    {
        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);
        $groupData = $this->mockGroupData();
        $this->facade->addGroup($groupData['name'], $this->rolesTransfer);

        $removed = $this->facade->removeRole($roleDto->getIdAclRole());
        $this->assertTrue($removed);

        try {
            $this->facade->getRoleById($roleDto->getIdAclRole());
        } catch (EmptyEntityException $e) {
            $this->assertInstanceOf('\Spryker\Zed\Acl\Business\Exception\EmptyEntityException', $e);
        }
    }

    /**
     * @return void
     */
    public function testAddRoleAndIsPresentInGroup()
    {
        $groupData = $this->mockGroupData();
        $roleData = $this->mockRoleData();
        $transferRole = $this->facade->addRole($roleData['name']);
        $this->facade->addGroup($groupData['name'], $this->rolesTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\RoleTransfer', $transferRole);
        $this->assertNotNull($transferRole->getIdAclRole());
        $this->assertEquals($roleData['name'], $transferRole->getName());
    }

    /**
     * @return void
     */
    public function testAddRuleAndAddToRole()
    {
        $groupData = $this->mockGroupData();
        $this->facade->addGroup($groupData['name'], $this->rolesTransfer);
        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        foreach ($ruleData as $current) {
            $ruleTransfer = new RuleTransfer();
            $ruleTransfer->fromArray($current, true);

            $ruleDto = $this->facade->addRule($ruleTransfer);

            $this->assertInstanceOf('\Generated\Shared\Transfer\RuleTransfer', $ruleDto);
            $this->assertNotNull($ruleDto->getIdAclRule());
            $this->assertEquals($current['bundle'], $ruleDto->getBundle());
            $this->assertEquals($current['controller'], $ruleDto->getController());
            $this->assertEquals($current['action'], $ruleDto->getAction());
            $this->assertEquals($current['type'], $ruleDto->getType());
            $this->assertEquals($roleDto->getIdAclRole(), $ruleDto->getFkAclRole());
        }
    }

    /**
     * @return void
     */
    public function testGetRulesFromRoles()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name'], $this->rolesTransfer);
        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        $rulesCollectionDto = $this->facade->getRoleRules($roleDto->getIdAclRole());
        $this->assertInstanceOf('\Generated\Shared\Transfer\RulesTransfer', $rulesCollectionDto);

        $index = 0;
        foreach ($rulesCollectionDto->getRules() as $current) {
            $currentData = $ruleData[$index];
            $this->assertEquals($currentData['bundle'], $current->getBundle());
            $this->assertEquals($currentData['controller'], $current->getController());
            $this->assertEquals($currentData['action'], $current->getAction());
            $this->assertEquals($currentData['type'], $current->getType());
            $this->assertEquals($roleDto->getIdAclRole(), $current->getFkAclRole());
            $index++;
        }
    }

    /**
     * @return void
     */
    public function testGetRulesFromGroup()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name'], $this->rolesTransfer);
        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        $rulesCollectionDto = $this->facade->getGroupRules($groupDto->getIdAclGroup());
        $this->assertInstanceOf('\Generated\Shared\Transfer\RulesTransfer', $rulesCollectionDto);

        $index = 0;
        foreach ($rulesCollectionDto->getRules() as $current) {
            $currentData = $ruleData[$index];
            $this->assertEquals($currentData['bundle'], $current->getBundle());
            $this->assertEquals($currentData['controller'], $current->getController());
            $this->assertEquals($currentData['action'], $current->getAction());
            $this->assertEquals($currentData['type'], $current->getType());
            $this->assertEquals($roleDto->getIdAclRole(), $current->getFkAclRole());
            $index++;
        }
    }

    /**
     * @return void
     */
    public function testRemoveRule()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name'], $this->rolesTransfer);
        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        foreach ($ruleData as $current) {
            $ruleTransfer = new RuleTransfer();
            $ruleTransfer->fromArray($current, true);
            $ruleDto = $this->facade->addRule($ruleTransfer);

            $removed = $this->facade->removeRule($ruleDto->getIdAclRule());
            $this->assertTrue($removed);

            try {
                $this->facade->getRule($ruleDto->getIdAclRule());
            } catch (RuleNotFoundException $e) {
                $this->assertInstanceOf('\Spryker\Zed\Acl\Business\Exception\RuleNotFoundException', $e);
            }
        }
    }

    /**
     * @return void
     */
    public function testAddUserToGroup()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name'], $this->rolesTransfer);
        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $added = $this->facade->addUserToGroup($userDto->getIdUser(), $groupDto->getIdAclGroup());
        $this->assertEquals($added, 1);
    }

    /**
     * @return void
     */
    public function testGetUserGroups()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name'], $this->rolesTransfer);
        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $added = $this->facade->addUserToGroup($userDto->getIdUser(), $groupDto->getIdAclGroup());
        $this->assertEquals($added, 1);

        $userGroupDto = $this->facade->getUserGroups($userDto->getIdUser());
        $this->assertInstanceOf('\Generated\Shared\Transfer\GroupsTransfer', $userGroupDto);

        $groups = $userGroupDto->toArray();
        $group = $groups['groups'][0];
        $this->assertNotNull($group['id_acl_group']);
        $this->assertEquals($groupData['name'], $group['name']);
    }

    /**
     * @return void
     */
    public function testCheckPermissionSimple()
    {
        $groupData = $this->mockGroupData();

        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);
        $groupDto = $this->facade->addGroup($groupData['name'], $this->rolesTransfer);
        $this->facade->addRoleToGroup($roleDto->getIdAclRole(), $groupDto->getIdAclGroup());

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $added = $this->facade->addUserToGroup($userDto->getIdUser(), $groupDto->getIdAclGroup());
        $this->assertEquals($added, 1);

        foreach ($ruleData as $current) {
            $ruleTransfer = new RuleTransfer();
            $ruleTransfer->fromArray($current, true);
            $this->facade->addRule($ruleTransfer);

            $shouldAllow = $current['type'] === 'allow' ? true : false;

            $canAccess = $this->facade
                ->checkAccess($userDto, $current['bundle'], $current['controller'], $current['action']);

            $this->assertEquals($shouldAllow, $canAccess);
        }
    }

    /**
     * @return void
     */
    public function testCheckPermissionSimpleHasNoAccess()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name'], $this->rolesTransfer);
        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $added = $this->facade->addUserToGroup($userDto->getIdUser(), $groupDto->getIdAclGroup());
        $this->assertEquals($added, 1);

        foreach ($ruleData as $current) {
            $ruleTransfer = new RuleTransfer();
            $ruleTransfer->fromArray($current, true);
            $this->facade->addRule($ruleTransfer);

            $canAccess = $this->facade
                ->checkAccess($userDto, rand(100, 999), rand(100, 999), rand(100, 999));

            $this->assertEquals(false, $canAccess);
        }
    }

    /**
     * @return void
     */
    public function testCheckPermissionWildcards()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name'], $this->rolesTransfer);
        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);

        $this->facade->addRoleToGroup($roleDto->getIdAclRole(), $groupDto->getIdAclGroup());

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $added = $this->facade->addUserToGroup($userDto->getIdUser(), $groupDto->getIdAclGroup());
        $this->assertEquals($added, 1);

        foreach ($ruleData as $current) {
            $ruleTransfer = new RuleTransfer();
            $ruleTransfer->fromArray($current, true);
            $ruleTransfer->setBundle('*');
            $ruleTransfer->setFkAclRole($roleDto->getIdAclRole());
            $this->facade->addRule($ruleTransfer);

            $shouldAllow = $current['type'] === 'allow' ? true : false;

            $canAccess = $this->facade
                ->checkAccess($userDto, $current['bundle'], $current['controller'], $current['action']);

            $this->assertEquals($shouldAllow, $canAccess);
        }
    }

    /**
     * @return void
     */
    public function testPermissionsWithSystemUser()
    {
        $systemUsers = $this->userFacade->getSystemUsers();

        $this->assertInstanceOf('\Generated\Shared\Transfer\CollectionTransfer', $systemUsers);

        foreach ($systemUsers as $user) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $user);

            $hasAccess = $this->facade->checkAccess($user, 'auth', 'login', 'index');
            $this->assertTrue($hasAccess);

            $hasAccess = $this->facade->checkAccess($user, 'auth', 'login', 'check');
            $this->assertTrue($hasAccess);
        }
    }

    /**
     * @return void
     */
    public function testPermissionsWithSystemUserShouldNotAllow()
    {
        $systemUsers = $this->userFacade->getSystemUsers();

        $this->assertInstanceOf('\Generated\Shared\Transfer\CollectionTransfer', $systemUsers);

        foreach ($systemUsers as $user) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $user);

            $hasAccess = $this->facade->checkAccess($user, rand(100, 999), rand(100, 999), rand(100, 999));
            $this->assertEquals(false, $hasAccess);

            $hasAccess = $this->facade->checkAccess($user, rand(100, 999), rand(100, 999), rand(100, 999));
            $this->assertEquals(false, $hasAccess);
        }
    }
}
