<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\Acl\AclConstants;
use Spryker\Zed\Acl\Business\Exception\RuleNotFoundException;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;

interface RuleInterface
{

    /**
     * @param RuleTransfer $ruleTransfer
     *
     * @throws RuleNotFoundException
     *
     * @return mixed
     */
    public function addRule(RuleTransfer $ruleTransfer);

    /**
     * @param RuleTransfer $RuleTransfer
     *
     * @throws RuleNotFoundException
     *
     * @return RuleTransfer
     */
    public function save(RuleTransfer $RuleTransfer);

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
        $bundle = AclConstants::VALIDATOR_WILDCARD,
        $controller = AclConstants::VALIDATOR_WILDCARD,
        $action = AclConstants::VALIDATOR_WILDCARD
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
     * @param UserTransfer $userTransfer
     *
     * @throws UserNotFoundException
     */
    public function registerSystemUserRules(UserTransfer $userTransfer);

    /**
     * @param UserTransfer $userTransfer
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAllowed(UserTransfer $userTransfer, $bundle, $controller, $action);

}
