<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use SprykerFeature\Shared\Acl\Transfer\RoleCollection;
use SprykerFeature\Shared\Acl\Transfer\Rule as transferRule;
use SprykerFeature\Shared\Acl\Transfer\RuleCollection;
use SprykerFeature\Shared\User\Transfer\User;
use SprykerFeature\Zed\Acl\Business\AclSettings;
use SprykerFeature\Zed\Acl\Business\Exception\RuleNotFoundException;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;

interface RuleInterface
{
    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param int $idRole
     * @param string $type
     *
     * @return transferRule
     * @throws RuleNotFoundException
     */
    public function addRule($bundle, $controller, $action, $idRole, $type = 'allow');

    /**
     * @param transferRule $data
     *
     * @return transferRule
     * @throws RuleNotFoundException
     */
    public function save(transferRule $data);

    /**
     * @param int $idRule
     *
     * @return bool
     */
    public function hasRule($idRule);

    /**
     * @param int $idRole
     *
     * @return RuleCollection
     */
    public function getRoleRules($idRole);

    /**
     * @param RoleCollection $roles
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return RuleCollection
     */
    public function findByRoles(
        RoleCollection $roles,
        $bundle = AclSettings::VALIDATOR_WILDCARD,
        $controller = AclSettings::VALIDATOR_WILDCARD,
        $action = AclSettings::VALIDATOR_WILDCARD
    );
    /**
     * @param int $idGroup
     *
     * @return RuleCollection
     */
    public function findByGroupId($idGroup);

    /**
     * @param int $id
     *
     * @return transferRule
     * @throws RuleNotFoundException
     */
    public function getRuleById($id);

    /**
     * @param int $id
     *
     * @return bool
     * @throws RuleNotFoundException
     */
    public function removeRuleById($id);

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isIgnorable($bundle, $controller, $action);

    /**
     * @param User $user
     *
     * @throws UserNotFoundException
     */
    public function registerSystemUserRules(User $user);

    /**
     * @param User $user
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAllowed(User $user, $bundle, $controller, $action);
}
