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
use Spryker\Zed\Acl\Persistence\AclQueryContainerInterface;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;

class Rule implements RuleInterface
{
    /**
     * @var \Spryker\Zed\Acl\Business\Model\GroupInterface
     */
    protected $group;

    /**
     * @var \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\Acl\Business\Model\RuleValidatorInterface
     */
    protected $rulesValidator;

    /**
     * @var \Spryker\Zed\Acl\AclConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Acl\Business\Model\GroupInterface $group
     * @param \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Acl\Dependency\Facade\AclToUserInterface $facadeUser
     * @param \Spryker\Zed\Acl\Business\Model\RuleValidatorInterface $rulesValidator
     * @param \Spryker\Zed\Acl\AclConfig $config
     */
    public function __construct(
        GroupInterface $group,
        AclQueryContainerInterface $queryContainer,
        AclToUserInterface $facadeUser,
        RuleValidatorInterface $rulesValidator,
        AclConfig $config
    ) {
        $this->group = $group;
        $this->queryContainer = $queryContainer;
        $this->userFacade = $facadeUser;
        $this->rulesValidator = $rulesValidator;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleTransfer $ruleTransfer
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
        $ruleTransfer->fromArray($aclRuleEntity->toArray(), true);

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
     * @return \Generated\Shared\Transfer\RulesTransfer
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
     * @deprecated Will be removed in the next major.
     *
     * @param \Generated\Shared\Transfer\RolesTransfer $roles
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return \Generated\Shared\Transfer\RulesTransfer
     */
    public function findByRoles(
        RolesTransfer $roles,
        $bundle = AclConstants::VALIDATOR_WILDCARD,
        $controller = AclConstants::VALIDATOR_WILDCARD,
        $action = AclConstants::VALIDATOR_WILDCARD
    ) {
        $ruleCollection = $this->queryContainer->queryRuleByPathAndRoles($roles, $bundle, $controller, $action)->find();

        $rulesTransfer = new RulesTransfer();

        foreach ($ruleCollection as $ruleEntity) {
            $ruleTransfer = new RuleTransfer();
            $ruleTransfer->fromArray($ruleEntity->toArray(), true);
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
        $roleCollection = $this->queryContainer->queryGroupRules($relationshipCollection)->find();

        $rulesTransfer = new RulesTransfer();

        foreach ($roleCollection as $ruleEntity) {
            $ruleTransfer = new RuleTransfer();
            $ruleTransfer->fromArray($ruleEntity->toArray(), true);
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
        $ruleTransfer->fromArray($aclRuleEntity->toArray(), true);

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
        $ignoredRules = $this->config->getRules();

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
        $credentials = $this->config->getCredentials();

        $credential = array_filter($credentials, function ($username) use ($userTransfer) {
            return $username === $userTransfer->getUsername();
        }, ARRAY_FILTER_USE_KEY);

        if (count($credential) === 0) {
            throw new UserNotFoundException();
        }

        foreach ($credential[$userTransfer->getUsername()]['rules'] as $rule) {
            $this->config->setRules($rule['bundle'], $rule['controller'], $rule['action'], $rule['type']);
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
        if ($this->userFacade->isSystemUser($userTransfer)) {
            $this->registerSystemUserRules($userTransfer);
        }

        if ($this->isIgnorable($bundle, $controller, $action)) {
            return true;
        }

        $groups = $this->group->getUserGroups($userTransfer->getIdUser());
        if (!$groups->getGroups()) {
            return false;
        }

        $this->provideUserRuleWhitelist();

        foreach ($groups->getGroups() as $group) {
            $rulesTransfer = $this->getRulesForGroupId($group->getIdAclGroup());

            if (!$rulesTransfer->getRules()) {
                continue;
            }

            $this->rulesValidator->setRules($rulesTransfer);
            $isAccessible = $this->rulesValidator->isAccessible($bundle, $controller, $action);

            if ($isAccessible) {
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
        $ruleWhitelist = $this->config->getUserRuleWhitelist();

        foreach ($ruleWhitelist as $rule) {
            $rulesTransfer = new RuleTransfer();
            $rulesTransfer->fromArray($rule, true);
            $this->rulesValidator->addRule($rulesTransfer);
        }
    }
}
