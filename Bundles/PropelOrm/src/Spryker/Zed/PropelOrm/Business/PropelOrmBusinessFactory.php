<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PropelOrm\Business\Builder\Collector\ClassNamespacesCollector;
use Spryker\Zed\PropelOrm\Business\Builder\Collector\ClassNamespacesCollectorInterface;
use Spryker\Zed\PropelOrm\PropelOrmDependencyProvider;

class PropelOrmBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PropelOrm\Business\Builder\Collector\ClassNamespacesCollectorInterface
     */
    public function createFindClassNamespacesCollector(): ClassNamespacesCollectorInterface
    {
        return new ClassNamespacesCollector(
            $this->getFindExtensionPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\PropelOrm\Business\Builder\Collector\ClassNamespacesCollectorInterface
     */
    public function createtPostSaveClassNamespacesCollector(): ClassNamespacesCollectorInterface
    {
        return new ClassNamespacesCollector(
            $this->getPostSaveExtensionPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\PropelOrmExtension\Dependency\Plugin\FindExtensionPluginInterface>
     */
    public function getFindExtensionPlugins(): array
    {
        return $this->getProvidedDependency(PropelOrmDependencyProvider::PLUGINS_FIND_EXTENSION);
    }

    /**
     * @return array<\Spryker\Zed\PropelOrmExtension\Dependency\Plugin\PostSaveExtensionPluginInterface>
     */
    public function getPostSaveExtensionPlugins(): array
    {
        return $this->getProvidedDependency(PropelOrmDependencyProvider::PLUGINS_POST_SAVE_EXTENSION);
    }
}
