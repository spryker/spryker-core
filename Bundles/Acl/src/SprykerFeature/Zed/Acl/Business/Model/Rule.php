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
    public function addRule($bundle, $controller, $action, $idRole, $type = 'allow')
    {
        $data = new RuleTransfer();

        $data->setBundle($bundle);
        $data->setController($controller);
        $data->setAction($action);
        $data->setType($type);
        $data->setFkAclRole($idRole);

        return $this->save($data);
    }

    /**
     * @param RuleTransfer $data
     *
     * @throws RuleNotFoundException
     *
     * @return RuleTransfer
     */
    public function save(RuleTransfer $data)
    {
        $entity = new SpyAclRule();

        if ($data->getIdAclRule() !== null && $this->hasRule($data->getIdAclRule()) === true) {
            throw new RuleNotFoundException();

        }

        if ($data->getIdAclRule() !== null) {
            $entity->setIdAclRule($data->getIdAclRule());
        }

        $entity->setFkAclRole($data->getFkAclRole());
        $entity->setBundle($data->getBundle());
        $entity->setController($data->getController());
        $entity->setAction($data->getAction());
        $entity->setType($data->getType());
        $entity->save();

        $transfer = new RuleTransfer();
        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }

    /**
     * @param int $idRule
     *
     * @return bool
     */
    public function hasRule($idRule)
    {
        $entity = $this->queryContainer->queryRuleById($idRule)->count();

        return $entity > 0;
    }

    /**
     * @param int $idRole
     *
     * @return RuleTransfer
     */
    public function getRoleRules($idRole)
    {
        $role = new RoleTransfer();
        $role->setIdAclRole($idRole);

        $roles = new RolesTransfer();
        $roles->addRole($role);

        $rules = $this->findByRoles($roles);

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

        $collection = new RulesTransfer();

        foreach ($results as $result) {
            $transfer = new RuleTransfer();
            Copy::entityToTransfer($transfer, $result);
            $collection->addRule($transfer);
        }

        return $collection;
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

        $collection = new RulesTransfer();

        foreach ($results as $result) {
            $transfer = new RuleTransfer();
            Copy::entityToTransfer($transfer, $result);
            $collection->addRule($transfer);
        }

        return $collection;
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
        $entity = $this->queryContainer->queryRuleById($id)->findOne();

        if ($entity === null) {
            throw new RuleNotFoundException();
        }

        $transfer = new RuleTransfer();
        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
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
        $entity = $this->queryContainer->queryRuleById($id)->delete();

        if ($entity <= 0) {
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
        $ignore = $this->settings->getRules();

        foreach ($ignore as $arrayRule) {
            $rule = new RuleTransfer();
            $rule->setBundle($arrayRule['bundle']);
            $rule->setController($arrayRule['controller']);
            $rule->setAction($arrayRule['action']);
            $rule->setType($arrayRule['type']);

            $this->rulesValidator->addRule($rule);
        }

        return $this->rulesValidator->isAccessible($bundle, $controller, $action);
    }

    /**
     * @param UserTransfer $user
     *
     * @throws UserNotFoundException
     */
    public function registerSystemUserRules(UserTransfer $user)
    {
        $credentials = $this->settings->getCredentials();

        $credential = array_filter($credentials, function ($username) use ($user) {
            return $username === $user->getUsername();
        }, ARRAY_FILTER_USE_KEY);

        if (count($credential) === 0) {
            throw new UserNotFoundException();
        }

        foreach ($credential[$user->getUsername()]['rules'] as $rule) {
            $this->settings->setRules($rule['bundle'], $rule['controller'], $rule['action'], $rule['type']);
        }
    }

    /**
     * @param UserTransfer $user
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAllowed(UserTransfer $user, $bundle, $controller, $action)
    {
        if ($this->facadeUser->isSystemUser($user)) {
            $this->registerSystemUserRules($user);
        }

        if ($this->isIgnorable($bundle, $controller, $action)) {
            return true;
        }

        $group = $this->group->getUserGroup($user->getIdUser());
        $rules = $this->getRulesForGroupId($group->getIdAclGroup());

        if ($this->facadeUser->isSystemUser($user)) {
            return false;
        }

        $group = $this->group->getUserGroup($user->getIdUser());
        if ($group === null) {
            return false;
        }

        $rules = $this->getRulesForGroupId($group->getIdAclGroup());
        if ($rules === null) {
            return false;
        }

        $this->rulesValidator->setRules($rules);

        return $this->rulesValidator->isAccessible($bundle, $controller, $action);
    }

}
