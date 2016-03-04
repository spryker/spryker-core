<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RulesTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Acl\Persistence\SpyAclRule;
use Spryker\Shared\Acl\AclConstants;
use Spryker\Zed\Acl\AclConfig;
use Spryker\Zed\Acl\Business\Exception\RuleNotFoundException;
use Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface;
use Spryker\Zed\Acl\Persistence\AclQueryContainer;
use Spryker\Zed\Library\Copy;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;

class Rule implements RuleInterface
{

    /**
     * @var \Spryker\Zed\Acl\Business\Model\Group
     */
    protected $group;

    /**
     * @var \Spryker\Zed\Acl\Persistence\AclQueryContainer
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\User\Business\UserFacade
     */
    protected $facadeUser;

    /**
     * @var \Spryker\Zed\Acl\Business\Model\RuleValidator
     */
    protected $rulesValidator;

    /**
     * @var \Spryker\Zed\Acl\AclConfig
     */
    protected $settings;

    /**
     * @param \Spryker\Zed\Acl\Business\Model\GroupInterface $group
     * @param \Spryker\Zed\Acl\Persistence\AclQueryContainer $queryContainer
     * @param \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface $facadeUser
     * @param \Spryker\Zed\Acl\Business\Model\RuleValidator $rulesValidator
     * @param \Spryker\Zed\Acl\Business\Model\RuleValidator $rulesValidator
     * @param \Spryker\Zed\Acl\AclConfig $settings
     */
    public function __construct(
        GroupInterface $group,
        AclQueryContainer $queryContainer,
        AclToUserInterface $facadeUser,
        RuleValidator $rulesValidator,
        AclConfig $settings
    ) {
        $this->group = $group;
        $this->queryContainer = $queryContainer;
        $this->facadeUser = $facadeUser;
        $this->rulesValidator = $rulesValidator;
        $this->settings = $settings;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\RuleNotFoundException
     *
     * @return \Generated\Shared\Transfer\RuleTransfer
     */
    public function addRule(RuleTransfer $ruleTransfer)
    {
        return $this->save($ruleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\RuleNotFoundException
     *
     * @return \Generated\Shared\Transfer\RuleTransfer
     */
    public function save(RuleTransfer $ruleTransfer)
    {
        $aclRuleEntity = new SpyAclRule();

        if ($ruleTransfer->getIdAclRule() !== null && $this->hasRule($ruleTransfer->getIdAclRule()) === true) {
            throw new RuleNotFoundException();
        }

        $aclRuleEntity->fromArray($ruleTransfer->toArray());
        $aclRuleEntity->save();

        $ruleTransfer = new RuleTransfer();
        $ruleTransfer = Copy::entityToTransfer($ruleTransfer, $aclRuleEntity);

        return $ruleTransfer;
    }

    /**
     * @param int $idRule
     *
     * @return bool
     */
    public function hasRule($idRule)
    {
        $aclRuleEntity = $this->queryContainer->queryRuleById($idRule)->count();

        return $aclRuleEntity > 0;
    }

    /**
     * @param int $idRole
     *
     * @return \Generated\Shared\Transfer\RuleTransfer
     */
    public function getRoleRules($idRole)
    {
        $roleTransfer = new RoleTransfer();
        $roleTransfer->setIdAclRole($idRole);

        $rolesTransfer = new RolesTransfer();
        $rolesTransfer->addRole($roleTransfer);

        $rules = $this->findByRoles($rolesTransfer);

        return $rules;
    }

    /**
     * @param int $idAclRole
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param int $type
     *
     * @return bool
     */
    public function existsRoleRule($idAclRole, $bundle, $controller, $action, $type)
    {
        $query = $this->queryContainer
            ->queryRuleByPathAndRole($idAclRole, $bundle, $controller, $action, $type);

        return ($query->count() > 0);
    }

    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $roles
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return \Generated\Shared\Transfer\RoleTransfer
     */
    public function findByRoles(
        RolesTransfer $roles,
        $bundle = AclConstants::VALIDATOR_WILDCARD,
        $controller = AclConstants::VALIDATOR_WILDCARD,
        $action = AclConstants::VALIDATOR_WILDCARD
    ) {
        $results = $this->queryContainer->queryRuleByPathAndRoles($roles, $bundle, $controller, $action)->find();

        $rulesTransfer = new RulesTransfer();

        foreach ($results as $result) {
            $ruleTransfer = new RuleTransfer();
            Copy::entityToTransfer($ruleTransfer, $result);
            $rulesTransfer->addRule($ruleTransfer);
        }

        return $rulesTransfer;
    }

    /**
     * @param int $idGroup
     *
     * @return \Generated\Shared\Transfer\RulesTransfer
     */
    public function getRulesForGroupId($idGroup)
    {
        $relationshipCollection = $this->queryContainer->queryGroupHasRole($idGroup)->find();
        $results = $this->queryContainer->queryGroupRules($relationshipCollection)->find();

        $rulesTransfer = new RulesTransfer();

        foreach ($results as $result) {
            $ruleTransfer = new RuleTransfer();
            Copy::entityToTransfer($ruleTransfer, $result);
            $rulesTransfer->addRule($ruleTransfer);
        }

        return $rulesTransfer;
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\RuleNotFoundException
     *
     * @return \Generated\Shared\Transfer\RuleTransfer
     */
    public function getRuleById($id)
    {
        $aclRuleEntity = $this->queryContainer->queryRuleById($id)->findOne();

        if ($aclRuleEntity === null) {
            throw new RuleNotFoundException();
        }

        $ruleTransfer = new RuleTransfer();
        $ruleTransfer = Copy::entityToTransfer($ruleTransfer, $aclRuleEntity);

        return $ruleTransfer;
    }

    /**
     * @param int $id
     *
     * @throws \Spryker\Zed\Acl\Business\Exception\RuleNotFoundException
     *
     * @return bool
     */
    public function removeRuleById($id)
    {
        $aclRuleEntity = $this->queryContainer->queryRuleById($id)->delete();

        if ($aclRuleEntity <= 0) {
            throw new RuleNotFoundException();
        }

        return true;
    }

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isIgnorable($bundle, $controller, $action)
    {
        $ignoredRules = $this->settings->getRules();

        foreach ($ignoredRules as $arrayRule) {
            $ruleTransfer = new RuleTransfer();
            $ruleTransfer->setBundle($arrayRule['bundle']);
            $ruleTransfer->setController($arrayRule['controller']);
            $ruleTransfer->setAction($arrayRule['action']);
            $ruleTransfer->setType($arrayRule['type']);

            $this->rulesValidator->addRule($ruleTransfer);
        }

        return $this->rulesValidator->isAccessible($bundle, $controller, $action);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return void
     */
    public function registerSystemUserRules(UserTransfer $userTransfer)
    {
        $credentials = $this->settings->getCredentials();

        $credential = array_filter($credentials, function ($username) use ($userTransfer) {
            return $username === $userTransfer->getUsername();
        }, ARRAY_FILTER_USE_KEY);

        if (count($credential) === 0) {
            throw new UserNotFoundException();
        }

        foreach ($credential[$userTransfer->getUsername()]['rules'] as $rule) {
            $this->settings->setRules($rule['bundle'], $rule['controller'], $rule['action'], $rule['type']);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAllowed(UserTransfer $userTransfer, $bundle, $controller, $action)
    {
        if ($this->facadeUser->isSystemUser($userTransfer)) {
            $this->registerSystemUserRules($userTransfer);
        }

        if ($this->isIgnorable($bundle, $controller, $action)) {
            return true;
        }

        $groups = $this->group->getUserGroups($userTransfer->getIdUser());
        if (empty($groups->getGroups())) {
            return false;
        }

        $this->provideUserRuleWhitelist();

        foreach ($groups->getGroups() as $group) {
            $rulesTransfer = $this->getRulesForGroupId($group->getIdAclGroup());

            if (empty($rulesTransfer->getRules())) {
                continue;
            }

            $this->rulesValidator->setRules($rulesTransfer);
            $isAccesible = $this->rulesValidator->isAccessible($bundle, $controller, $action);

            if ($isAccesible) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return void
     */
    protected function provideUserRuleWhitelist()
    {
        $ruleWhitelist = $this->settings->getUserRuleWhitelist();

        foreach ($ruleWhitelist as $rule) {
            $rulesTransfer = new RuleTransfer();
            $rulesTransfer->fromArray($rule, true);
            $this->rulesValidator->addRule($rulesTransfer);
        }
    }

}
