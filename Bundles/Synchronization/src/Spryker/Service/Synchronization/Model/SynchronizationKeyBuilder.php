<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Synchronization\Model;

use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Service\Synchronization\Plugin\BaseKeyGenerator;

class SynchronizationKeyBuilder implements SynchronizationKeyBuilderInterface
{
    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected $defaultKeyGeneratorPlugin;

    /**
     * @var array<\Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface>
     */
    protected $storageSyncKeyGeneratorPlugins;

    /**
     * @var array<\Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface>
     */
    protected $searchSyncKeyGeneratorPlugins;

    /**
     * @param \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface $defaultKeyGeneratorPlugin
     * @param array<\Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface> $storageSyncKeyGeneratorPlugins
     * @param array<\Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface> $searchSyncKeyGeneratorPlugins
     */
    public function __construct(
        SynchronizationKeyGeneratorPluginInterface $defaultKeyGeneratorPlugin,
        array $storageSyncKeyGeneratorPlugins,
        array $searchSyncKeyGeneratorPlugins
    ) {
        $this->defaultKeyGeneratorPlugin = $defaultKeyGeneratorPlugin;
        $this->storageSyncKeyGeneratorPlugins = $storageSyncKeyGeneratorPlugins;
        $this->searchSyncKeyGeneratorPlugins = $searchSyncKeyGeneratorPlugins;
    }

    /**
     * @param string $resourceName
     *
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    public function getStorageKeyBuilder($resourceName)
    {
        if (!array_key_exists($resourceName, $this->storageSyncKeyGeneratorPlugins)) {
            $keyGeneratorPlugin = $this->defaultKeyGeneratorPlugin;
        } else {
            $keyGeneratorPlugin = $this->storageSyncKeyGeneratorPlugins[$resourceName];
        }

        if ($keyGeneratorPlugin instanceof BaseKeyGenerator) {
            $keyGeneratorPlugin->setResource($resourceName);
        }

        return $keyGeneratorPlugin;
    }
}
