<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\RulesTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerFeature\Zed\Acl\AclConfig;
use SprykerFeature\Zed\Acl\Business\Exception\RuleNotFoundException;
use SprykerFeature\Zed\Acl\Dependency\Facade\AclToUserInterface;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclRule;
use SprykerFeature\Zed\Library\Copy;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;
use SprykerFeature\Zed\User\Business\UserFacade;

class Rule implements RuleInterface
{

    /**
     * @var Group
     */
    private $group;

    /**
     * @var AclQueryContainer
     */
    protected $queryContainer;

    /**
     * @var UserFacade
     */
    private $facadeUser;

    /**
     * @var RuleValidator
     */
    protected $rulesValidator;

    /**
     * @var AclConfig
     */
    protected $settings;

    /**
     * @param GroupInterface $group
     * @param AclQueryContainer $queryContainer
     * @param AclToUserInterface $facadeUser
     * @param RuleValidator $rulesValidator
     * @param RuleValidator $rulesValidator
     * @param AclConfig $settings
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
     * @param RuleTransfer $ruleTransfer
     *
     * @throws RuleNotFoundException
     * @return RuleTransfer
     */
    public function addRule(RuleTransfer $ruleTransfer)
    {
        return $this->save($ruleTransfer);
    }

    /**
     * @param RuleTransfer $ruleTransfer
     *
     * @throws RuleNotFoundException
     *
     * @return RuleTransfer
     */
    public function save(RuleTransfer $ruleTransfer)
    {
        $aclRuleEntity = new SpyAclRule();

        if ($ruleTransfer->getIdAclRule() !== null && $this->hasRule($ruleTransfer->getIdAclRule()) === true) {
            throw new RuleNotFoundException();
        }

        if ($ruleTransfer->getIdAclRule() !== null) {
            $aclRuleEntity->setIdAclRule($ruleTransfer->getIdAclRule());
        }

        $aclRuleEntity->setFkAclRole($ruleTransfer->getFkAclRole());
        $aclRuleEntity->setBundle($ruleTransfer->getBundle());
        $aclRuleEntity->setController($ruleTransfer->getController());
        $aclRuleEntity->setAction($ruleTransfer->getAction());
        $aclRuleEntity->setType($ruleTransfer->getType());
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
     * @return RuleTransfer
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
            ->queryRuleByPathAndRole($idAclRole, $bundle, $controller, $action, $type)
        ;

        return ($query->count() > 0);
    }

    /**
     * @param RolesTransfer $roles
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return RoleTransfer
     */
    public function findByRoles(
        RolesTransfer $roles,
        $bundle = AclConfig::VALIDATOR_WILDCARD,
        $controller = AclConfig::VALIDATOR_WILDCARD,
        $action = AclConfig::VALIDATOR_WILDCARD
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
     * @return RulesTransfer
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
     * @throws RuleNotFoundException
     *
     * @return RuleTransfer
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
     * @throws RuleNotFoundException
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
     * @param UserTransfer $userTransfer
     *
     * @throws UserNotFoundException
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
     * @param UserTransfer $userTransfer
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

        $group = $this->group->getUserGroup($userTransfer->getIdUser());
        if ($group === null) {
            return false;
        }

        $rulesTransfer = $this->getRulesForGroupId($group->getIdAclGroup());
        if ($rulesTransfer === null) {
            return false;
        }

        $this->rulesValidator->setRules($rulesTransfer);

        return $this->rulesValidator->isAccessible($bundle, $controller, $action);



    }

}
