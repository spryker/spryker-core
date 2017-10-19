<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Synchronization;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Synchronization\Model\KeyFilter;
use Spryker\Service\Synchronization\Model\SynchronizationKeyBuilder;
use Spryker\Service\Synchronization\Plugin\DefaultKeyGeneratorPlugin;

class SynchronizationServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Synchronization\Model\SynchronizationKeyBuilderInterface
     */
    public function createSynchronizationKeyBuilder()
    {
        return new SynchronizationKeyBuilder(
            $this->createDefaultKeyGeneratorPlugin(),
            $this->getSynchronizationStorageKeyGeneratorPlugins(),
            $this->getSynchronizationSearchKeyGeneratorPlugins()
        );
    }

    /**
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface[]
     */
    protected function getSynchronizationStorageKeyGeneratorPlugins()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::SYNCHRONIZATION_STORAGE_KEY_GENERATOR_PLUGINS);
    }

    /**
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface[]
     */
    protected function getSynchronizationSearchKeyGeneratorPlugins()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::SYNCHRONIZATION_SEARCH_KEY_GENERATOR_PLUGINS);
    }

    /**
     * @return \Spryker\Service\Synchronization\Plugin\DefaultKeyGeneratorPlugin
     */
    protected function createDefaultKeyGeneratorPlugin()
    {
        return new DefaultKeyGeneratorPlugin(
            $this->createKeyFilter()
        );
    }

    /**
     * @return \Spryker\Service\Synchronization\Model\KeyFilterInterface
     */
    public function createKeyFilter()
    {
        return new KeyFilter();
    }
}
