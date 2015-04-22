<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

use SprykerFeature\Zed\Library\Copy;
use SprykerFeature\Shared\Acl\Transfer\RoleCollection;
use SprykerFeature\Shared\Acl\Transfer\RuleCollection;
use SprykerFeature\Shared\User\Transfer\User;
use SprykerFeature\Zed\Acl\Business\AclSettings;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Shared\Acl\Transfer\Rule as TransferRule;

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
     * @var AclSettings
     */
    protected $settings;

    /**
     * @param AclQueryContainer $queryContainer
     * @param LocatorLocatorInterface $locator
     * @param RuleValidator $rulesValidator
     * @param AclSettings $settings
     */
    public function __construct(
        AclQueryContainer $queryContainer,
        LocatorLocatorInterface $locator,
        RuleValidator $rulesValidator,
        AclSettings $settings
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
     * @return TransferRule
     * @throws RuleNotFoundException
     */
    public function addRule($bundle, $controller, $action, $idRole, $type = 'allow')
    {
        $data = $this->locator->acl()->transferRule();

        $data->setBundle($bundle);
        $data->setController($controller);
        $data->setAction($action);
        $data->setType($type);
        $data->setFkAclRole($idRole);

        return $this->save($data);
    }

    /**
     * @param TransferRule $data
     *
     * @return TransferRule
     * @throws RuleNotFoundException
     */
    public function save(TransferRule $data)
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

        $transfer = $this->locator->acl()->transferRule();
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
     * @return RuleCollection
     */
    public function getRoleRules($idRole)
    {
        $role = $this->locator->acl()->transferRole();
        $role->setIdAclRole($idRole);

        $roles = $this->locator->acl()->transferRoleCollection();
        $roles->add($role);

        $rules = $this->findByRoles($roles);

        return $rules;
    }

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
    ) {
        $results = $this->queryContainer->queryRuleByPathAndRoles($roles, $bundle, $controller, $action)->find();

        $collection = $this->locator->acl()->transferRuleCollection();
        $collection = Copy::entityCollectionToTransferCollection($collection, $results, false);

        return $collection;
    }

    /**
     * @param int $idGroup
     *
     * @return RuleCollection
     */
    public function findByGroupId($idGroup)
    {
        $relationshipCollection = $this->queryContainer->queryGroupHasRole($idGroup)->find();
        $results = $this->queryContainer->queryGroupRules($relationshipCollection)->find();

        $collection = $this->locator->acl()->transferRuleCollection();
        $collection = Copy::entityCollectionToTransferCollection($collection, $results, false);

        return $collection;
    }

    /**
     * @param int $id
     *
     * @return TransferRule
     * @throws RuleNotFoundException
     */
    public function getRuleById($id)
    {
        $entity = $this->queryContainer->queryRuleById($id)->findOne();

        if ($entity === null) {
            throw new RuleNotFoundException();
        }

        $transfer = $this->locator->acl()->transferRule();
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
            $rule = $this->locator->acl()->transferRule();
            $rule->setBundle($arrayRule['bundle']);
            $rule->setController($arrayRule['controller']);
            $rule->setAction($arrayRule['action']);
            $rule->setType($arrayRule['type']);

            $this->rulesValidator->addRule($rule);
        }

        return $this->rulesValidator->isAccessible($bundle, $controller, $action);
    }

    /**
     * @param User $user
     *
     * @throws UserNotFoundException
     */
    public function registerSystemUserRules(User $user)
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
     * @param User $user
     * @param string $bundle
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function isAllowed(User $user, $bundle, $controller, $action)
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
