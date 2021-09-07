<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclEntityDataImport;

use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AclEntityDataImport\AclEntityDataImportConfig getConfig()
 */
class AclEntityDataImportDependencyProvider extends DataImportDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_ACL_ENTITY = 'SERVICE_ACL_ENTITY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addAclEntityService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclEntityService(Container $container): Container
    {
        $container->set(static::SERVICE_ACL_ENTITY, function (Container $container) {
            return $container->getLocator()->aclEntity()->service();
        });

        return $container;
    }
}
