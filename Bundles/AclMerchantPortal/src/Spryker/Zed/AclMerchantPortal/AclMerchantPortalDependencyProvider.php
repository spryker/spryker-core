<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal;

use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeBridge;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeBridge;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToMerchantFacadeBridge;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToMerchantUserFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig getConfig()
 */
class AclMerchantPortalDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_ACL_ENTITY = 'FACADE_ACL_ENTITY';

    /**
     * @var string
     */
    public const FACADE_ACL = 'FACADE_ACL';

    /**
     * @var string
     */
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_ACL_RULE_EXPANDER = 'PLUGINS_MERCHANT_ACL_RULE_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_ACL_ENTITY_RULE_EXPANDER = 'PLUGINS_MERCHANT_ACL_ENTITY_RULE_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_USER_ACL_RULE_EXPANDER = 'PLUGINS_MERCHANT_USER_ACL_RULE_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_ACL_ENTITY_CONFIGURATION_EXPANDER = 'PLUGINS_ACL_ENTITY_CONFIGURATION_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_USER_ACL_ENTITY_RULE_EXPANDER = 'PLUGINS_MERCHANT_USER_ACL_ENTITY_RULE_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addAclEntityFacade($container);
        $container = $this->addAclFacade($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addMerchantAclEntityRuleExpanderPlugins($container);
        $container = $this->addMerchantAclRuleExpanderPlugins($container);
        $container = $this->addMerchantUserAclEntityRuleExpanderPlugins($container);
        $container = $this->addMerchantUserAclRuleExpanderPlugins($container);
        $container = $this->addAclEntityConfigurationExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclFacade(Container $container): Container
    {
        $container->set(static::FACADE_ACL, function (Container $container) {
            return new AclMerchantPortalToAclFacadeBridge(
                $container->getLocator()->acl()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclEntityFacade(Container $container): Container
    {
        $container->set(static::FACADE_ACL_ENTITY, function (Container $container) {
            return new AclMerchantPortalToAclEntityFacadeBridge(
                $container->getLocator()->aclEntity()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new AclMerchantPortalToMerchantFacadeBridge(
                $container->getLocator()->merchant()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return new AclMerchantPortalToMerchantUserFacadeBridge(
                $container->getLocator()->merchantUser()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantAclEntityRuleExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_ACL_ENTITY_RULE_EXPANDER, function () {
            return $this->getMerchantAclEntityRuleExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclEntityRuleExpanderPluginInterface>
     */
    protected function getMerchantAclEntityRuleExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantAclRuleExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_ACL_RULE_EXPANDER, function () {
            return $this->getMerchantAclRuleExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclRuleExpanderPluginInterface>
     */
    protected function getMerchantAclRuleExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserAclEntityRuleExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_USER_ACL_ENTITY_RULE_EXPANDER, function () {
            return $this->getMerchantUserAclEntityRuleExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclEntityRuleExpanderPluginInterface>
     */
    protected function getMerchantUserAclEntityRuleExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserAclRuleExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_USER_ACL_RULE_EXPANDER, function () {
            return $this->getMerchantUserAclRuleExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclRuleExpanderPluginInterface>
     */
    protected function getMerchantUserAclRuleExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclEntityConfigurationExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ACL_ENTITY_CONFIGURATION_EXPANDER, function () {
            return $this->getAclEntityConfigurationExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface>
     */
    protected function getAclEntityConfigurationExpanderPlugins(): array
    {
        return [];
    }
}
