<?php

namespace Functional\SprykerFeature\Zed\User;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use Generated\Shared\Transfer\UserUserTransfer;
use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Acl\Business\Exception\EmptyEntityException;
use SprykerFeature\Zed\Acl\Business\Exception\RuleNotFoundException;
use SprykerFeature\Zed\User\Business\UserFacade;
use SprykerEngine\Zed\Kernel\Business\Factory;

/**
 * @group AclTest
 */
class AclTest extends Test
{
    /**
     * @var AclFacade $facade
     */
    private $facade;

    /**
     * @var UserFacade $facade
     */
    private $userFacade;

    /**
     * @var AutoCompletion
     */
    private $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();

        $this->facade = new AclFacade(
            new Factory('Acl'),
            $this->locator
        );

        $this->userFacade = new UserFacade(
            new Factory('User'),
            $this->locator
        );
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
     * @param int $idGroup
     *
     * @return array
     */
    private function mockRoleData($idGroup)
    {
        $data['name'] = sprintf('name-%s', rand(100, 999));
        $data['idGroup'] = $idGroup;

        return $data;
    }

    /**
     * @param string $type
     * @param int $idRole
     *
     * @return mixed
     */
    private function mockRuleData($type, $idRole)
    {
        $data['bundle'] = sprintf('bundle-%s', rand(100, 999));
        $data['controller'] = sprintf('controller-%s', rand(100, 999));
        $data['action'] = sprintf('action-%s', rand(100, 999));
        $data['type'] = $type;
        $data['idRole'] = $idRole;

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
     * @param $data
     *
     * @return User
     */
    private function mockAddUser($data)
    {
        return $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);
    }

    /**
     * @group Acl
     */
    public function testAddGroup()
    {
        $data = $this->mockGroupData();

        $transfer = $this->facade->addGroup($data['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $transfer);
        $this->assertNotNull($transfer->getIdAclGroup());
        $this->assertEquals($data['name'], $transfer->getName());
    }

    /**
     * @group Acl
     */
    public function testUpdateGroup()
    {
        $groupData = $this->mockGroupData();
        $groupData2 = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $dto2 = clone $groupDto;
        $dto2->setName($groupData2['name']);
        $this->facade->updateGroup($dto2);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $dto2);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertNotEquals($groupData2['name'], $groupDto->getName());
        $this->assertEquals($groupData2['name'], $dto2->getName());
    }

    /**
     * @group Acl
     */
    public function testGetGroupById()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);

        $id = $groupDto->getIdAclGroup();

        unset($groupDto);

        $groupDto = $this->facade->getGroup($id);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($id, $groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());
    }

    /**
     * @group Acl
     */
    public function testRemoveGroup()
    {
        $groupData = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $removed = $this->facade->removeGroup($groupDto->getIdAclGroup());
        $this->assertTrue($removed);

        try {
            $this->facade->getGroup($groupDto->getIdAclGroup());
        } catch (EmptyEntityException $e) {
            $this->assertInstanceOf('\SprykerFeature\Zed\Acl\Business\Exception\EmptyEntityException', $e);
        }
    }

    /**
     * @group Acl
     */
    public function testAddRole()
    {
        $groupData = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());

        $roleDto = $this->facade->addRole($roleData['name'], $groupDto->getIdAclGroup());

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRoleTransfer', $roleDto);
        $this->assertNotNull($roleDto->getIdAclRole());
        $this->assertEquals($roleData['name'], $roleDto->getName());
    }

    /**
     * @group Acl
     */
    public function testRemoveRole()
    {
        $groupData = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());

        $roleDto = $this->facade->addRole($roleData['name'], $groupDto->getIdAclGroup());

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRoleTransfer', $roleDto);
        $this->assertNotNull($roleDto->getIdAclRole());
        $this->assertEquals($roleData['name'], $roleDto->getName());

        $removed = $this->facade->removeRole($roleDto->getIdAclRole());
        $this->assertTrue($removed);

        try {
            $this->facade->getRoleById($roleDto->getIdAclRole());
        } catch (EmptyEntityException $e) {
            $this->assertInstanceOf('\SprykerFeature\Zed\Acl\Business\Exception\EmptyEntityException', $e);
        }
    }

    /**
     * @group Acl
     */
    public function testAddRoleAndIsPresentInGroup()
    {
        $groupData = $this->mockGroupData();

        $transferGroup = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $transferGroup);
        $this->assertNotNull($transferGroup->getIdAclGroup());
        $this->assertEquals($groupData['name'], $transferGroup->getName());

        $roleData = $this->mockRoleData($transferGroup->getIdAclGroup());

        $transferRole = $this->facade->addRole($roleData['name'], $transferGroup->getIdAclGroup());

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRoleTransfer', $transferRole);
        $this->assertNotNull($transferRole->getIdAclRole());
        $this->assertEquals($roleData['name'], $transferRole->getName());

        $transferRoleCollection = $this->facade->getGroupRoles($transferGroup->getIdAclGroup());

        $isPresent = false;
        foreach ($transferRoleCollection as $current) {
            if ($transferRole->getIdAclRole() === $current->getIdAclRole()) {
                $isPresent = true;
            }
        }

        $this->assertTrue($isPresent);
    }

    /**
     * @group Acl
     */
    public function testAddRuleAndAddToRole()
    {
        $groupData = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());

        $roleDto = $this->facade->addRole($roleData['name'], $groupDto->getIdAclGroup());

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRoleTransfer', $roleDto);
        $this->assertNotNull($roleDto->getIdAclRole());
        $this->assertEquals($roleData['name'], $roleDto->getName());

        $roles = $this->facade->getGroupRoles($groupDto->getIdAclGroup());

        $isPresent = false;
        foreach ($roles as $current) {
            if ($roleDto->getIdAclRole() === $current->getIdAclRole()) {
                $isPresent = true;
            }
        }

        $this->assertTrue($isPresent);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        foreach ($ruleData as $current) {
            $ruleDto = $this->facade
                            ->addRule(
                                $current['bundle'],
                                $current['controller'],
                                $current['action'],
                                $roleDto->getIdAclRole(),
                                $current['type']
                            );

            $this->assertInstanceOf('\Generated\Shared\Transfer\AclRuleTransfer', $ruleDto);
            $this->assertNotNull($ruleDto->getIdAclRule());
            $this->assertEquals($current['bundle'], $ruleDto->getBundle());
            $this->assertEquals($current['controller'], $ruleDto->getController());
            $this->assertEquals($current['action'], $ruleDto->getAction());
            $this->assertEquals($current['type'], $ruleDto->getType());
            $this->assertEquals($roleDto->getIdAclRole(), $ruleDto->getFkAclRole());
        }
    }

    /**
     * @group Acl
     */
    public function testGetRulesFromRoles()
    {
        $groupData = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());

        $roleDto = $this->facade->addRole($roleData['name'], $groupDto->getIdAclGroup());

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRoleTransfer', $roleDto);
        $this->assertNotNull($roleDto->getIdAclRole());
        $this->assertEquals($roleData['name'], $roleDto->getName());

        $roles = $this->facade->getGroupRoles($groupDto->getIdAclGroup());

        $isPresent = false;
        foreach ($roles as $current) {
            if ($roleDto->getIdAclRole() === $current->getIdAclRole()) {
                $isPresent = true;
            }
        }

        $this->assertTrue($isPresent);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        foreach ($ruleData as $current) {
            $transferRule = $this->facade
                            ->addRule(
                                $current['bundle'],
                                $current['controller'],
                                $current['action'],
                                $roleDto->getIdAclRole(),
                                $current['type']
                            );

            $this->assertInstanceOf('\Generated\Shared\Transfer\AclRuleTransfer', $transferRule);
            $this->assertNotNull($transferRule->getIdAclRule());
            $this->assertEquals($current['bundle'], $transferRule->getBundle());
            $this->assertEquals($current['controller'], $transferRule->getController());
            $this->assertEquals($current['action'], $transferRule->getAction());
            $this->assertEquals($current['type'], $transferRule->getType());
            $this->assertEquals($roleDto->getIdAclRole(), $transferRule->getFkAclRole());
        }

        $rulesCollectionDto = $this->facade->getRoleRules($roleDto->getIdAclRole());
        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRuleCollectionTransfer', $rulesCollectionDto);
        $this->assertEquals(count($ruleData), count($rulesCollectionDto));

        $index = 0;
        foreach ($rulesCollectionDto as $current) {
            $currentData = $ruleData[$index];
            $this->assertInstanceOf('\Generated\Shared\Transfer\AclRuleTransfer', $transferRule);
            $this->assertEquals($currentData['bundle'], $current->getBundle());
            $this->assertEquals($currentData['controller'], $current->getController());
            $this->assertEquals($currentData['action'], $current->getAction());
            $this->assertEquals($currentData['type'], $current->getType());
            $this->assertEquals($roleDto->getIdAclRole(), $current->getFkAclRole());
            $index++;
        }
    }

    /**
     * @group Acl
     */
    public function testGetRulesFromGroup()
    {
        $groupData = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());

        $roleDto = $this->facade->addRole($roleData['name'], $groupDto->getIdAclGroup());

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRoleTransfer', $roleDto);
        $this->assertNotNull($roleDto->getIdAclRole());
        $this->assertEquals($roleData['name'], $roleDto->getName());

        $roles = $this->facade->getGroupRoles($groupDto->getIdAclGroup());

        $isPresent = false;
        foreach ($roles as $current) {
            if ($roleDto->getIdAclRole() === $current->getIdAclRole()) {
                $isPresent = true;
            }
        }

        $this->assertTrue($isPresent);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        foreach ($ruleData as $current) {
            $ruleDto = $this->facade
                            ->addRule(
                                $current['bundle'],
                                $current['controller'],
                                $current['action'],
                                $roleDto->getIdAclRole(),
                                $current['type']
                            );

            $this->assertInstanceOf('\Generated\Shared\Transfer\AclRuleTransfer', $ruleDto);
            $this->assertNotNull($ruleDto->getIdAclRule());
            $this->assertEquals($current['bundle'], $ruleDto->getBundle());
            $this->assertEquals($current['controller'], $ruleDto->getController());
            $this->assertEquals($current['action'], $ruleDto->getAction());
            $this->assertEquals($current['type'], $ruleDto->getType());
            $this->assertEquals($roleDto->getIdAclRole(), $ruleDto->getFkAclRole());
        }

        $rulesCollectionDto = $this->facade->getGroupRules($groupDto->getIdAclGroup());
        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRuleTransfer', $rulesCollectionDto);
        $this->assertEquals(count($ruleData), count($rulesCollectionDto));

        $index = 0;
        foreach ($rulesCollectionDto as $current) {
            $currentData = $ruleData[$index];
            $this->assertInstanceOf('\Generated\Shared\Transfer\AclRuleTransfer', $ruleDto);
            $this->assertEquals($currentData['bundle'], $current->getBundle());
            $this->assertEquals($currentData['controller'], $current->getController());
            $this->assertEquals($currentData['action'], $current->getAction());
            $this->assertEquals($currentData['type'], $current->getType());
            $this->assertEquals($roleDto->getIdAclRole(), $current->getFkAclRole());
            $index++;
        }
    }

    /**
     * @group Acl
     */
    public function testRemoveRule()
    {
        $groupData = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());

        $roleDto = $this->facade->addRole($roleData['name'], $groupDto->getIdAclGroup());

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRoleTransfer', $roleDto);
        $this->assertNotNull($roleDto->getIdAclRole());
        $this->assertEquals($roleData['name'], $roleDto->getName());

        $roles = $this->facade->getGroupRoles($groupDto->getIdAclGroup());

        $isPresent = false;
        foreach ($roles as $current) {
            if ($roleDto->getIdAclRole() === $current->getIdAclRole()) {
                $isPresent = true;
            }
        }

        $this->assertTrue($isPresent);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        foreach ($ruleData as $current) {
            $ruleDto = $this->facade
                            ->addRule(
                                $current['bundle'],
                                $current['controller'],
                                $current['action'],
                                $roleDto->getIdAclRole(),
                                $current['type']
                            );

            $this->assertInstanceOf('\Generated\Shared\Transfer\AclRuleTransfer', $ruleDto);
            $this->assertNotNull($ruleDto->getIdAclRule());
            $this->assertEquals($current['bundle'], $ruleDto->getBundle());
            $this->assertEquals($current['controller'], $ruleDto->getController());
            $this->assertEquals($current['action'], $ruleDto->getAction());
            $this->assertEquals($current['type'], $ruleDto->getType());
            $this->assertEquals($roleDto->getIdAclRole(), $ruleDto->getFkAclRole());

            $removed = $this->facade->removeRule($ruleDto->getIdAclRule());
            $this->assertTrue($removed);

            try {
                $this->facade->getRule($ruleDto->getIdAclRule());
            } catch (RuleNotFoundException $e) {
                $this->assertInstanceOf('\SprykerFeature\Zed\Acl\Business\Exception\RuleNotFoundException', $e);
            }
        }
    }

    /**
     * @group Acl
     */
    public function testAddUserToGroup()
    {
        $groupData = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $added = $this->facade->addUserToGroup($userDto->getIdUserUser(), $groupDto->getIdAclGroup());
        $this->assertEquals($added, 1);
    }

    /**
     * @group Acl
     */
    public function testGetUserGroup()
    {
        $groupData = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());

        $roleDto = $this->facade->addRole($roleData['name'], $groupDto->getIdAclGroup());

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRoleTransfer', $roleDto);
        $this->assertNotNull($roleDto->getIdAclRole());
        $this->assertEquals($roleData['name'], $roleDto->getName());

        $roles = $this->facade->getGroupRoles($groupDto->getIdAclGroup());

        $isPresent = false;
        foreach ($roles as $current) {
            if ($roleDto->getIdAclRole() === $current->getIdAclRole()) {
                $isPresent = true;
            }
        }

        $this->assertTrue($isPresent);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $added = $this->facade->addUserToGroup($userDto->getIdUserUser(), $groupDto->getIdAclGroup());
        $this->assertEquals($added, 1);

        $userGroupDto = $this->facade->getUserGroup($userDto->getIdUserUser());
        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $userGroupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());
    }

    /**
     * @group Acl
     */
    public function testCheckPermissionSimple()
    {
        $groupData = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());

        $roleDto = $this->facade->addRole($roleData['name'], $groupDto->getIdAclGroup());

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRoleTransfer', $roleDto);
        $this->assertNotNull($roleDto->getIdAclRole());
        $this->assertEquals($roleData['name'], $roleDto->getName());

        $roles = $this->facade->getGroupRoles($groupDto->getIdAclGroup());

        $isPresent = false;
        foreach ($roles as $current) {
            if ($roleDto->getIdAclRole() === $current->getIdAclRole()) {
                $isPresent = true;
            }
        }

        $this->assertTrue($isPresent);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $added = $this->facade->addUserToGroup($userDto->getIdUserUser(), $groupDto->getIdAclGroup());
        $this->assertEquals($added, 1);

        foreach ($ruleData as $current) {
            $ruleDto = $this->facade
                ->addRule(
                    $current['bundle'],
                    $current['controller'],
                    $current['action'],
                    $roleDto->getIdAclRole(),
                    $current['type']
                );

            $this->assertNotNull($ruleDto->getIdAclRule());
            $this->assertInstanceOf('\Generated\Shared\Transfer\AclRuleTransfer', $ruleDto);
            $this->assertEquals($current['bundle'], $ruleDto->getBundle());
            $this->assertEquals($current['controller'], $ruleDto->getController());
            $this->assertEquals($current['action'], $ruleDto->getAction());
            $this->assertEquals($current['type'], $ruleDto->getType());
            $this->assertEquals($roleDto->getIdAclRole(), $ruleDto->getFkAclRole());

            $shouldAllow = $current['type'] === 'allow' ? true : false;

            $canAccess = $this->facade
                ->checkAccess($userDto, $current['bundle'], $current['controller'], $current['action']);

            $this->assertEquals($shouldAllow, $canAccess);
        }
    }

    /**
     * @group Acl
     */
    public function testCheckPermissionSimpleHasNoAccess()
    {
        $groupData = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());

        $roleDto = $this->facade->addRole($roleData['name'], $groupDto->getIdAclGroup());

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRoleTransfer', $roleDto);
        $this->assertNotNull($roleDto->getIdAclRole());
        $this->assertEquals($roleData['name'], $roleDto->getName());

        $roles = $this->facade->getGroupRoles($groupDto->getIdAclGroup());

        $isPresent = false;
        foreach ($roles as $current) {
            if ($roleDto->getIdAclRole() === $current->getIdAclRole()) {
                $isPresent = true;
            }
        }

        $this->assertTrue($isPresent);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $added = $this->facade->addUserToGroup($userDto->getIdUserUser(), $groupDto->getIdAclGroup());
        $this->assertEquals($added, 1);

        foreach ($ruleData as $current) {
            $ruleDto = $this->facade
                ->addRule(
                    $current['bundle'],
                    $current['controller'],
                    $current['action'],
                    $roleDto->getIdAclRole(),
                    $current['type']
                );

            $this->assertNotNull($ruleDto->getIdAclRule());
            $this->assertInstanceOf('\Generated\Shared\Transfer\AclRuleTransfer', $ruleDto);
            $this->assertEquals($current['bundle'], $ruleDto->getBundle());
            $this->assertEquals($current['controller'], $ruleDto->getController());
            $this->assertEquals($current['action'], $ruleDto->getAction());
            $this->assertEquals($current['type'], $ruleDto->getType());
            $this->assertEquals($roleDto->getIdAclRole(), $ruleDto->getFkAclRole());

            $canAccess = $this->facade
                ->checkAccess($userDto, rand(100, 999), rand(100, 999), rand(100, 999));

            $this->assertEquals(false, $canAccess);
        }
    }

    /**
     * @group Acl
     */
    public function testCheckPermissionWildcards()
    {
        $groupData = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclGroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());

        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());

        $roleDto = $this->facade->addRole($roleData['name'], $groupDto->getIdAclGroup());

        $this->assertInstanceOf('\Generated\Shared\Transfer\AclRoleTransfer', $roleDto);
        $this->assertNotNull($roleDto->getIdAclRole());
        $this->assertEquals($roleData['name'], $roleDto->getName());

        $roles = $this->facade->getGroupRoles($groupDto->getIdAclGroup());

        $isPresent = false;
        foreach ($roles as $current) {
            if ($roleDto->getIdAclRole() === $current->getIdAclRole()) {
                $isPresent = true;
            }
        }

        $this->assertTrue($isPresent);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $added = $this->facade->addUserToGroup($userDto->getIdUserUser(), $groupDto->getIdAclGroup());
        $this->assertEquals($added, 1);

        foreach ($ruleData as $current) {
            $ruleDto = $this->facade
                ->addRule('*', $current['controller'], $current['action'], $roleDto->getIdAclRole(), $current['type']);

            $this->assertNotNull($ruleDto->getIdAclRule());
            $this->assertInstanceOf('\Generated\Shared\Transfer\AclRuleTransfer', $ruleDto);
            $this->assertEquals($current['controller'], $ruleDto->getController());
            $this->assertEquals($current['action'], $ruleDto->getAction());
            $this->assertEquals($current['type'], $ruleDto->getType());
            $this->assertEquals($roleDto->getIdAclRole(), $ruleDto->getFkAclRole());

            $shouldAllow = $current['type'] === 'allow' ? true : false;

            $canAccess = $this->facade
                ->checkAccess($userDto, $current['bundle'], $current['controller'], $current['action']);

            $this->assertEquals($shouldAllow, $canAccess);
        }
    }

    /**
     * @group Acl
     */
    public function testPermissionsWithSystemUser()
    {
        $systemUsers = $this->userFacade->getSystemUsers();

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserUserTransfer', $systemUsers);

        foreach ($systemUsers as $user) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\UserUserTransfer', $user);

            $hasAccess = $this->facade->checkAccess($user, 'auth', 'login', 'index');
            $this->assertTrue($hasAccess);

            $hasAccess = $this->facade->checkAccess($user, 'auth', 'login', 'check');
            $this->assertTrue($hasAccess);
        }
    }

    /**
     * @group Acl
     */
    public function testPermissionsWithSystemUserShouldNotAllow()
    {
        $systemUsers = $this->userFacade->getSystemUsers();

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserUserTransfer', $systemUsers);

        foreach ($systemUsers as $user) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\UserUserTransfer', $user);

            $hasAccess = $this->facade->checkAccess($user, rand(100, 999), rand(100, 999), rand(100, 999));
            $this->assertEquals(false, $hasAccess);

            $hasAccess = $this->facade->checkAccess($user, rand(100, 999), rand(100, 999), rand(100, 999));
            $this->assertEquals(false, $hasAccess);
        }
    }
}
