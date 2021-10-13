<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\AclEntity\AclEntityDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AclEntity\AclEntityConfig getConfig()
 * @method \Spryker\Zed\AclEntity\Business\AclEntityFacadeInterface getFacade()
 * @method \Spryker\Zed\AclEntity\Communication\AclEntityCommunicationFactory getFactory()
 */
class AclEntityApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container->set(AclEntityDependencyProvider::IS_ACL_ENTITY_ENABLED, true);
        $container->configure(AclEntityDependencyProvider::IS_ACL_ENTITY_ENABLED, ['isGlobal' => true]);

        return $container;
    }
}
