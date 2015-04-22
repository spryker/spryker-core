<?php
namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Acl\Transfer\GroupCollection;
use SprykerFeature\Shared\Acl\Transfer\RoleCollection;
use SprykerFeature\Shared\Acl\Transfer\RuleCollection;
use SprykerFeature\Zed\Acl\Business\AclSettings;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;

class Installer implements InstallerInterface
{
    /**
     * @var AclQueryContainer
     */
    protected $queryContainer;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var AclSettings
     */
    protected $settings;

    /**
     * @param AclQueryContainer $queryContainer
     * @param LocatorLocatorInterface $locator
     * @param AclSettings $settings
     */
    public function __construct(
        AclQueryContainer $queryContainer,
        LocatorLocatorInterface $locator,
        AclSettings $settings
    ) {
        $this->queryContainer = $queryContainer;
        $this->locator = $locator;
        $this->settings = $settings;
    }

    /**
     * Main Installation Method
     */
    public function install()
    {
        $groups = $this->addGroups($this->settings->getInstallerGroups());
        $roles = $this->addRoles($this->settings->getInstallerRoles(), $groups->toArray());
        $this->addRules($this->settings->getInstallerRules(), $roles->toArray());
        $this->addUserRelationships($this->settings->getInstallerUsers(), $groups->toArray());
    }

    /**
     * @param array $groupsArray
     *
     * @return GroupCollection
     */
    protected function addGroups(array $groupsArray)
    {
        $groupCollection = $this->locator->acl()->transferGroupCollection();

        foreach ($groupsArray as $group) {
            if ($this->queryContainer->queryGroupByName($group['name'])->count() > 0) {
                continue;
            }

            $groupTransfer = $this->locator->acl()
                ->facade()
                ->addGroup($group['name']);

            $groupCollection->add($groupTransfer);
        }

        return $groupCollection;
    }

    /**
     * @param array $roleArray
     * @param array $groupArray
     *
     * @return RoleCollection
     */
    protected function addRoles(array $roleArray, array $groupArray)
    {
        $roleCollection = $this->locator->acl()->transferRoleCollection();

        foreach ($roleArray as $role) {
            if ($this->queryContainer->queryRoleByName($role['name'])->count() > 0) {
                continue;
            }

            $group = array_filter($groupArray, function ($item) use ($role) {
                return $item['name'] === $role['group'];
            });

            //@TODO this is because our transfer collection returns negative indexes and i can't pick 0
            $group = array_shift($group);

            if (count($group) === 0) {
                continue;
            }

            $roleTransfer = $this->locator->acl()
                ->facade()
                ->addRole($role['name'], $group['id_acl_group']);

            $roleCollection->add($roleTransfer);
        }

        return $roleCollection;
    }

    /**
     * @param array $rulesArray
     * @param array $rolesArray
     *
     * @return RuleCollection
     */
    protected function addRules(array $rulesArray, array $rolesArray)
    {
        $ruleCollection = $this->locator->acl()->transferRuleCollection();

        foreach ($rulesArray as $rule) {
            $role = array_filter($rolesArray, function ($item) use ($rule) {
                return $item['name'] === $rule['role'];
            });

            if (count($role) === 0) {
                continue;
            }

            //@TODO this is because our transfer collection returns negative indexes and i can't pick 0
            $role = array_shift($role);

            $ruleTransfer = $this->locator->acl()
                ->facade()
                ->addRule($rule['bundle'], $rule['controller'], $rule['action'], $role['id_acl_role'], $rule['type']);

            $ruleCollection->add($ruleTransfer);
        }

        return $ruleCollection;
    }

    /**
     * @param array $arrayUsers
     * @param array $groupsArray
     *
     * @return bool
     */
    protected function addUserRelationships(array $arrayUsers, array $groupsArray)
    {
        foreach ($arrayUsers as $username => $data) {
            $group = array_filter($groupsArray, function ($item) use ($data) {
                return $item['name'] === $data['group'];
            });

            //@TODO this is because our transfer collection returns negative indexes and i can't pick 0
            $group = array_shift($group);

            $user = $this->locator->user()->facade()->getUserByUsername($username);
            $query = $this->queryContainer->queryUserHasGroupById($group['id_acl_group'], $user->getIdUserUser());

            if (count($group) === 0 || $query->count() > 0) {
                continue;
            }

            $this->locator->acl()
                ->facade()
                ->addUserToGroup($user->getIdUserUser(), $group['id_acl_group']);
        }

        return true;
    }
}
