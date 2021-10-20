<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal;

use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeBridge;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeBridge;
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
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addAclEntityFacade($container);
        $container = $this->addAclFacade($container);

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
}
