<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

use SprykerEngine\Zed\Transfer\Business\Model\TransferArrayObject;
use SprykerFeature\Zed\Acl\AclConfig;
use SprykerFeature\Zed\Library\Copy;
use Generated\Shared\Transfer\AclRoleTransfer;
use Generated\Shared\Transfer\UserUserTransfer;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use Generated\Shared\Transfer\AclRuleTransfer;

use SprykerFeature\Zed\Acl\Business\Exception\RuleNotFoundException;
use SprykerFeature\Zed\User\Business\Exception\UserNotFoundException;

class Rule implements RuleInterface
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
     * @var RuleValidator
     */
    protected $rulesValidator;

    /**
     * @var AclConfig
     */
    protected $settings;

    /**
     * @param AclQueryContainer $queryContainer
     * @param LocatorLocatorInterface $locator
     * @param RuleValidator $rulesValidator
     * @param AclConfig $settings
     */
    public function __construct(
        AclQueryContainer $queryContainer,
        LocatorLocatorInterface $locator,
        RuleValidator $rulesValidator,
        AclConfig $settings
    ) {
        $this->queryContainer = $queryContainer;
        $this->locator = $locator;
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
     * @return AclRuleTransfer
     * @throws RuleNotFoundException
     */
    public function addRule($bundle, $controller, $action, $idRole, $type = 'allow')
    {
        $data = new AclRuleTransfer();

        $data->setBundle($bundle);
        $data->setController($controller);
        $data->setAction($action);
        $data->setType($type);
        $data->setFkAclRole($idRole);

        return $this->save($data);
    }

    /**
     * @param AclRuleTransfer $data
     *
     * @return AclRuleTransfer
     * @throws RuleNotFoundException
     */
    public function save(AclRuleTransfer $data)
    {
        $entity = $this->locator->acl()->entitySpyAclRule();

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

        $transfer = new AclRuleTransfer();
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
     * @return AclRuleTransfer
     */
    public function getRoleRules($idRole)
    {
        $role = new AclRoleTransfer();
        $role->setIdAclRole($idRole);

        $roles = new AclRoleTransfer();
        $roles->add($role);

        $rules = $this->findByRoles($roles);

        return $rules;
    }

    /**
     * @param AclRoleTransfer $roles
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return AclRoleTransfer
     */
    public function findByRoles(
        AclRoleTransfer $roles,
        $bundle = AclConfig::VALIDATOR_WILDCARD,
        $controller = AclConfig::VALIDATOR_WILDCARD,
        $action = AclConfig::VALIDATOR_WILDCARD
    ) {
        $results = $this->queryContainer->queryRuleByPathAndRoles($roles, $bundle, $controller, $action)->find();

        $collection = new TransferArrayObject();

        foreach ($results as $result) {
            $transfer = new AclRuleTransfer();
            $collection->add(Copy::entityToTransfer($transfer, $result));
        }

        return $collection;
    }

    /**
     * @param int $idGroup
     *
     * @return AclRuleTransfer
     */
    public function findByGroupId($idGroup)
    {
        $relationshipCollection = $this->queryContainer->queryGroupHasRole($idGroup)->find();
        $results = $this->queryContainer->queryGroupRules($relationshipCollection)->find();

        $collection = new TransferArrayObject();

        foreach ($results as $result) {
            $transfer = new AclRuleTransfer();
            $collection->add(Copy::entityToTransfer($transfer, $result));
        }

        return $collection;
    }

    /**
     * @param int $id
     *
     * @return AclRuleTransfer
     * @throws RuleNotFoundException
     */
    public function getRuleById($id)
    {
        $entity = $this->queryContainer->queryRuleById($id)->findOne();

        if ($entity === null) {
            throw new RuleNotFoundException();
        }

        $transfer = new AclRuleTransfer();
        $transfer = Copy::entityToTransfer($transfer, $entity);

        return $transfer;
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws RuleNotFoundException
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
            $rule = new AclRuleTransfer();
            $rule->setBundle($arrayRule['bundle']);
            $rule->setController($arrayRule['controller']);
            $rule->setAction($arrayRule['action']);
            $rule->setType($arrayRule['type']);

            $this->rulesValidator->addRule($rule);
        }

        return $this->rulesValidator->isAccessible($bundle, $controller, $action);
    }

    /**
     * @param UserUserTransfer $user
     *
     * @throws UserNotFoundException
     */
    public function registerSystemUserRules(UserUserTransfer $user)
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
     * @param UserUserTransfer $user
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAllowed(UserUserTransfer $user, $bundle, $controller, $action)
    {
        if ($this->locator->user()->facade()->isSystemUser($user)) {
            $this->registerSystemUserRules($user);
        }

        if ($this->isIgnorable($bundle, $controller, $action)) {
            return true;
        }

        if ($this->locator->user()->facade()->isSystemUser($user)) {
            return false;
        }

        $group = $this->locator->acl()->facade()->getUserGroup($user->getIdUserUser());
        if ($group === null) {
            return false;
        }

        $rules = $this->locator->acl()->facade()->getGroupRules($group->getIdAclGroup());
        if ($rules === null) {
            return false;
        }

        $this->rulesValidator->setRules($rules);

        return $this->rulesValidator->isAccessible($bundle, $controller, $action);
    }
}
