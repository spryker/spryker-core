<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Acl;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Acl\AclDependencyProvider;
use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Acl\Business\Exception\EmptyEntityException;
use SprykerFeature\Zed\Acl\Business\Exception\RuleNotFoundException;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Zed\User\Business\UserFacade;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;

/**
 * @group Zed
 * @group Business
 * @group Acl
 * @group AclTest
 */
class AclTest extends Test
{

    /**
     * @var AclFacade
     */
    private $facade;

    /**
     * @var UserFacade
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

        $container = new Container();

        $dependencyProvider = new AclDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dependencyProvider->provideCommunicationLayerDependencies($container);
        $dependencyProvider->providePersistenceLayerDependencies($container);

        $this->facade = new AclFacade(
            new Factory('Acl'),
            $this->locator
        );
        $this->facade->setOwnQueryContainer(new AclQueryContainer(
            new \SprykerEngine\Zed\Kernel\Persistence\Factory('Acl'), $this->locator
        ));
        $this->facade->setExternalDependencies($container);

        $this->userFacade = new UserFacade(
            new Factory('User'),
            $this->locator
        );
        $this->userFacade->setOwnQueryContainer(new UserQueryContainer(
            new \SprykerEngine\Zed\Kernel\Persistence\Factory('User'), $this->locator
        ));
        $this->userFacade->setExternalDependencies($container);
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
     * @return mixed
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
     * @return UserTransfer
     */
    private function mockAddUser(array $data)
    {
        return $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);
    }

    public function testAddGroup()
    {
        $data = $this->mockGroupData();

        $transfer = $this->facade->addGroup($data['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\GroupTransfer', $transfer);
        $this->assertNotNull($transfer->getIdAclGroup());
        $this->assertEquals($data['name'], $transfer->getName());
    }

    public function testUpdateGroup()
    {
        $groupData = $this->mockGroupData();
        $groupData2 = $this->mockGroupData();

        $groupDto = $this->facade->addGroup($groupData['name']);

        $dto2 = clone $groupDto;
        $dto2->setName($groupData2['name']);
        $this->facade->updateGroup($dto2);

        $this->assertInstanceOf('\Generated\Shared\Transfer\GroupTransfer', $dto2);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertNotEquals($groupData2['name'], $groupDto->getName());
        $this->assertEquals($groupData2['name'], $dto2->getName());
    }

    public function testGetGroupById()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);

        $id = $groupDto->getIdAclGroup();

        unset($groupDto);

        $groupDto = $this->facade->getGroup($id);

        $this->assertInstanceOf('\Generated\Shared\Transfer\GroupTransfer', $groupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($id, $groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());
    }

    public function testRemoveGroup()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);

        $removed = $this->facade->removeGroup($groupDto->getIdAclGroup());
        $this->assertTrue($removed);

        try {
            $this->facade->getGroup($groupDto->getIdAclGroup());
        } catch (EmptyEntityException $e) {
            $this->assertInstanceOf('\SprykerFeature\Zed\Acl\Business\Exception\EmptyEntityException', $e);
        }
    }

    public function testAddRole()
    {
        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\RoleTransfer', $roleDto);
        $this->assertNotNull($roleDto->getIdAclRole());
        $this->assertEquals($roleData['name'], $roleDto->getName());
    }

    public function testRemoveRole()
    {
        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);

        $removed = $this->facade->removeRole($roleDto->getIdAclRole());
        $this->assertTrue($removed);

        try {
            $this->facade->getRoleById($roleDto->getIdAclRole());
        } catch (EmptyEntityException $e) {
            $this->assertInstanceOf('\SprykerFeature\Zed\Acl\Business\Exception\EmptyEntityException', $e);
        }
    }

    public function testAddRoleAndIsPresentInGroup()
    {
        $groupData = $this->mockGroupData();
        $roleData = $this->mockRoleData();
        $transferRole = $this->facade->addRole($roleData['name']);
        $transferGroup = $this->facade->addGroup($groupData['name']);

        $this->assertInstanceOf('\Generated\Shared\Transfer\RoleTransfer', $transferRole);
        $this->assertNotNull($transferRole->getIdAclRole());
        $this->assertEquals($roleData['name'], $transferRole->getName());
    }

    public function testAddRuleAndAddToRole()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);
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

    public function testGetRulesFromRoles()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);
        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());
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

    public function testGetRulesFromGroup()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);
        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());
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

    public function testRemoveRule()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);
        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());
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
                $this->assertInstanceOf('\SprykerFeature\Zed\Acl\Business\Exception\RuleNotFoundException', $e);
            }
        }
    }

    public function testAddUserToGroup()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);
        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $added = $this->facade->addUserToGroup($userDto->getIdUser(), $groupDto->getIdAclGroup());
        $this->assertEquals($added, 1);
    }

    public function testGetUserGroup()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);
        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());
        $roleDto = $this->facade->addRole($roleData['name']);

        $ruleData[] = $this->mockRuleData('allow', $roleDto->getIdAclRole());
        $ruleData[] = $this->mockRuleData('deny', $roleDto->getIdAclRole());

        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $added = $this->facade->addUserToGroup($userDto->getIdUser(), $groupDto->getIdAclGroup());
        $this->assertEquals($added, 1);

        $userGroupDto = $this->facade->getUserGroup($userDto->getIdUser());
        $this->assertInstanceOf('\Generated\Shared\Transfer\GroupTransfer', $userGroupDto);
        $this->assertNotNull($groupDto->getIdAclGroup());
        $this->assertEquals($groupData['name'], $groupDto->getName());
    }

    public function testCheckPermissionSimple()
    {
        $groupData = $this->mockGroupData();

        $roleData = $this->mockRoleData();
        $roleDto = $this->facade->addRole($roleData['name']);
        $groupDto = $this->facade->addGroup($groupData['name']);
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

    public function testCheckPermissionSimpleHasNoAccess()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);
        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());
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

    public function testCheckPermissionWildcards()
    {
        $groupData = $this->mockGroupData();
        $groupDto = $this->facade->addGroup($groupData['name']);
        $roleData = $this->mockRoleData($groupDto->getIdAclGroup());
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
