<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Form\DataProvider;

use Generated\Shared\Transfer\RuleTransfer;
use Orm\Zed\Acl\Persistence\Map\SpyAclRuleTableMap;
use Spryker\Zed\Acl\Business\AclFacade;
use Spryker\Zed\Acl\Communication\Form\RuleForm;
use Spryker\Zed\Acl\Dependency\Facade\AclToRouterFacadeInterface;

class AclRuleFormDataProvider
{
    /**
     * @var string
     */
    protected const ROOT_ACCESS = '*';

    /**
     * @var \Spryker\Zed\Acl\Business\AclFacade
     */
    protected $aclFacade;

    /**
     * @var \Spryker\Zed\Acl\Dependency\Facade\AclToRouterFacadeInterface
     */
    protected $routerFacade;

    /**
     * @param \Spryker\Zed\Acl\Business\AclFacade $aclFacade
     * @param \Spryker\Zed\Acl\Dependency\Facade\AclToRouterFacadeInterface $routerFacade
     */
    public function __construct(AclFacade $aclFacade, AclToRouterFacadeInterface $routerFacade)
    {
        $this->aclFacade = $aclFacade;
        $this->routerFacade = $routerFacade;
    }

    /**
     * @param int $idAclRole
     *
     * @return array
     */
    public function getData($idAclRole)
    {
        $ruleTransfer = new RuleTransfer();
        $ruleTransfer->setFkAclRole($idAclRole);

        return $ruleTransfer->toArray();
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions()
    {
        return [
            RuleForm::OPTION_TYPE => $this->getPermissionSelectChoices(),
        ];
    }

    /**
     * @param string|null $routeBundle
     * @param string|null $controller
     *
     * @return array<string, mixed>
     */
    public function getRouterOptions(?string $routeBundle = null, ?string $controller = null): array
    {
        $bundles = $this->routerFacade->getRouterBundleCollection()->getBundles();

        $controllers = [];
        if ($routeBundle) {
            $controllers = $this->routerFacade->getRouterControllerCollection($routeBundle)->getControllers();
        }

        $actions = [];
        if ($routeBundle && $controller) {
            $actions = $this->routerFacade->getRouterActionCollection($routeBundle, $controller)->getActions();
        }

        array_unshift($bundles, static::ROOT_ACCESS);
        array_unshift($controllers, static::ROOT_ACCESS);
        array_unshift($actions, static::ROOT_ACCESS);

        return [
            RuleForm::BUNDLE_FIELD_CHOICES => array_combine($bundles, $bundles),
            RuleForm::CONTROLLER_FIELD_CHOICES => array_combine($controllers, $controllers),
            RuleForm::ACTION_FIELD_CHOICES => array_combine($actions, $actions),
        ];
    }

    /**
     * @return array
     */
    protected function getPermissionSelectChoices()
    {
        return array_combine(
            SpyAclRuleTableMap::getValueSet(SpyAclRuleTableMap::COL_TYPE),
            SpyAclRuleTableMap::getValueSet(SpyAclRuleTableMap::COL_TYPE),
        ) ?: [];
    }
}
