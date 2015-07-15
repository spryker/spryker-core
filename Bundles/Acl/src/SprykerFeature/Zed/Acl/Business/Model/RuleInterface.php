<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\UserTransfer;
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
     * @throws RuleNotFoundException
     *
     * @return RuleTransfer
     */
    public function addRule($bundle, $controller, $action, $idRole, $type = 'allow');

    /**
     * @param RuleTransfer $data
     *
     * @throws RuleNotFoundException
     *
     * @return RuleTransfer
     */
    public function save(RuleTransfer $data);

    /**
     * @param int $idRule
     *
     * @return bool
     */
    public function hasRule($idRule);

    /**
     * @param int $idRole
     *
     * @return RuleTransfer
     */
    public function getRoleRules($idRole);

    /**
     * @param RolesTransfer $roles
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return RuleTransfer
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
     * @return RuleTransfer
     */
    public function getRulesForGroupId($idGroup);

    /**
     * @param int $id
     *
     * @throws RuleNotFoundException
     *
     * @return RuleTransfer
     */
    public function getRuleById($id);

    /**
     * @param int $id
     *
     * @throws RuleNotFoundException
     *
     * @return bool
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
     * @param UserTransfer $user
     *
     * @throws UserNotFoundException
     */
    public function registerSystemUserRules(UserTransfer $user);

    /**
     * @param UserTransfer $user
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAllowed(UserTransfer $user, $bundle, $controller, $action);

}
