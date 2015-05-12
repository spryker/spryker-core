<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\AclRoleTransfer;
use Generated\Shared\Transfer\AclRuleTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\UserUserTransfer;
use SprykerFeature\Zed\Acl\AclConfig;
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
     * @return AclRuleTransfer
     * @throws RuleNotFoundException
     */
    public function addRule($bundle, $controller, $action, $idRole, $type = 'allow');

    /**
     * @param AclRuleTransfer $data
     *
     * @return AclRuleTransfer
     * @throws RuleNotFoundException
     */
    public function save(AclRuleTransfer $data);

    /**
     * @param int $idRule
     *
     * @return bool
     */
    public function hasRule($idRule);

    /**
     * @param int $idRole
     *
     * @return AclRuleTransfer
     */
    public function getRoleRules($idRole);

    /**
     * @param RolesTransfer $roles
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return AclRuleTransfer
     */
    public function findByRoles(
        RolesTransfer $roles,
        $bundle = AclConfig::VALIDATOR_WILDCARD,
        $controller = AclConfig::VALIDATOR_WILDCARD,
        $action = AclConfig::VALIDATOR_WILDCARD
    );
    /**
     * @param int $idGroup
     *
     * @return AclRuleTransfer
     */
    public function findByGroupId($idGroup);

    /**
     * @param int $id
     *
     * @return AclRuleTransfer
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
     * @param UserUserTransfer $user
     *
     * @throws UserNotFoundException
     */
    public function registerSystemUserRules(UserUserTransfer $user);

    /**
     * @param UserUserTransfer $user
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAllowed(UserUserTransfer $user, $bundle, $controller, $action);
}
