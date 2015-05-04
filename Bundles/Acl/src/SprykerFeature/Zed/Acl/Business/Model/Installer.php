<?php
namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\AclGroupTransfer;
use Generated\Shared\Transfer\AclRoleTransfer;
use Generated\Shared\Transfer\AclRuleTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Transfer\Business\Model\TransferArrayObject;
use SprykerFeature\Zed\Acl\AclConfig;
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
     * @var AclConfig
     */
    protected $settings;

    /**
     * @param AclQueryContainer $queryContainer
     * @param LocatorLocatorInterface $locator
     * @param AclConfig $settings
     */
    public function __construct(
        AclQueryContainer $queryContainer,
        LocatorLocatorInterface $locator,
        AclConfig $settings
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
        $roles = $this->addRoles($this->settings->getInstallerRoles(), $groups->getArrayCopy());
        $this->addRules($this->settings->getInstallerRules(), $roles->getArrayCopy());
        $this->addUserRelationships($this->settings->getInstallerUsers(), $groups->getArrayCopy());
    }

    /**
     * @param array $groupsArray
     *
     * @return AclGroupTransfer
     */
    protected function addGroups(array $groupsArray)
    {
        $groupCollection = new TransferArrayObject();

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
     * @return AclRoleTransfer
     */
    protected function addRoles(array $roleArray, array $groupArray)
    {
        $roleCollection = new TransferArrayObject();

        foreach ($roleArray as $role) {
            if ($this->queryContainer->queryRoleByName($role['name'])->count() > 0) {
                continue;
            }

            $group = array_filter($groupArray, function ($item) use ($role) {
                return $item->getName() === $role['group'];
            });

            //@TODO this is because our transfer collection returns negative indexes and i can't pick 0
            $group = array_shift($group);

            if (count($group) === 0) {
                continue;
            }

            $roleTransfer = $this->locator->acl()
                ->facade()
                ->addRole($role['name'], $group->getIdAclGroup());

            $roleCollection->add($roleTransfer);
        }

        return $roleCollection;
    }

    /**
     * @param array $rulesArray
     * @param array $rolesArray
     *
     * @return AclRuleTransfer
     */
    protected function addRules(array $rulesArray, array $rolesArray)
    {
        $ruleCollection = new TransferArrayObject();

        foreach ($rulesArray as $rule) {
            $role = array_filter($rolesArray, function ($item) use ($rule) {
                return $item->getName() === $rule['role'];
            });

            if (count($role) === 0) {
                continue;
            }

            //@TODO this is because our transfer collection returns negative indexes and i can't pick 0
            $role = array_shift($role);

            $ruleTransfer = $this->locator->acl()
                ->facade()
                ->addRule($rule['bundle'], $rule['controller'], $rule['action'], $role->getIdAclRole(), $rule['type']);

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
                return $item->getName() === $data['group'];
            });

            //@TODO this is because our transfer collection returns negative indexes and i can't pick 0
            $group = array_shift($group);

            $user = $this->locator->user()->facade()->getUserByUsername($username);
            $query = $this->queryContainer->queryUserHasGroupById($group->getIdAclGroup(), $user->getIdUserUser());

            if (count($group) === 0 || $query->count() > 0) {
                continue;
            }

            $this->locator->acl()
                ->facade()
                ->addUserToGroup($user->getIdUserUser(), $group->getIdAclGroup());
        }

        return true;
    }
}
