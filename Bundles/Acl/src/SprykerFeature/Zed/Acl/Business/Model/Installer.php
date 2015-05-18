<?php
namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Acl\AclConfig;
use SprykerFeature\Zed\Acl\Business\AclFacade;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNotFoundException;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNotFoundException;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;
use SprykerFeature\Zed\User\Business\UserFacade;

class Installer implements InstallerInterface
{

    /**
     * @var AclFacade
     */
    private $facadeAcl;

    /**
     * @var UserFacade
     */
    private $facadeUser;

    /**
     * @var AclConfig
     */
    protected $config;

    /**
     * @param AclFacade $facadeAcl
     * @param UserFacade $facadeUser
     * @param AclConfig $settings
     */
    public function __construct(
        AclFacade $facadeAcl,
        UserFacade $facadeUser,
        AclConfig $settings
    ) {
        $this->facadeAcl = $facadeAcl;
        $this->facadeUser = $facadeUser;
        $this->config = $settings;
    }

    /**
     * Main Installation Method
     */
    public function install()
    {
        $this->addGroups();
        $this->addRoles();
        $this->addRules();
        $this->addUserGroupRelations();
    }

    private function addGroups()
    {
        foreach ($this->config->getInstallerGroups() as $group) {
            $this->addGroup($group['name']);
        }
    }

    /**
     * @param string $name
     */
    private function addGroup($name)
    {
        if (!$this->facadeAcl->hasGroupByName($name)) {
            $this->facadeAcl->addGroup($name);
        }
    }

    private function addRoles()
    {
        foreach ($this->config->getInstallerRoles() as $role) {
            if (!$this->facadeAcl->existsRoleByName($role['name'])) {
                $this->addRole($role);
            }
        }
    }

    /**
     * @param array $role
     * @throws GroupNotFoundException
     */
    private function addRole(array $role)
    {
        $group = $this->facadeAcl->getGroupByName($role['group']);
        if (!$group) {
            throw new GroupNotFoundException();
        }

        $this->facadeAcl->addRole($role['name'], $group->getIdAclGroup());
    }

    /**
     * @throws RoleNotFoundException
     */
    private function addRules()
    {
        foreach ($this->config->getInstallerRules() as $rule) {
            $role = $this->facadeAcl->getRoleByName($rule['role']);
            if (!$role) {
                throw new RoleNotFoundException();
            }

            if (!$this->facadeAcl->existsRoleRule($role->getIdAclRole(), $rule['bundle'], $rule['controller'], $rule['action'], $rule['type'])) {
                $this->facadeAcl->addRule(
                    $rule['bundle'], $rule['controller'], $rule['action'], $role->getIdAclRole(), $rule['type']
                );
            }
        }
    }

    /**
     * @throws GroupNotFoundException
     * @throws UserNotFoundException
     */
    private function addUserGroupRelations()
    {
        foreach ($this->config->getInstallerUsers() as $username => $config) {
            $group = $this->facadeAcl->getGroupByName($config['group']);
            if (!$group) {
                throw new GroupNotFoundException();
            }

            $user = $this->facadeUser->getUserByUsername($username);
            if (!$user) {
                throw new UserNotFoundException();
            }

            if (!$this->facadeAcl->userHasGroupId($group->getIdAclGroup(), $user->getIdUserUser())) {
                $this->facadeAcl->addUserToGroup($user->getIdUserUser(), $group->getIdAclGroup());
            }
        }
    }
}
