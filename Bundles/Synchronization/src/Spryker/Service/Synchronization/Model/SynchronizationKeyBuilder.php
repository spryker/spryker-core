<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Synchronization\Model;

use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Service\Synchronization\Plugin\AbstractKeyGenerator;

class SynchronizationKeyBuilder implements SynchronizationKeyBuilderInterface
{

    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected $defaultKeyGeneratorPlugin;

    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface[]
     */
    protected $storageSyncKeyGeneratorPlugins;

    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface[]
     */
    protected $searchSyncKeyGeneratorPlugins;

    /**
     * SynchronizationKeyBuilder constructor.
     *
     * @param \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface $defaultKeyGeneratorPlugin
     * @param \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface[] $storageSyncKeyGeneratorPlugins
     * @param \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface[] $searchSyncKeyGeneratorPlugins
     */
    public function __construct(SynchronizationKeyGeneratorPluginInterface $defaultKeyGeneratorPlugin, array $storageSyncKeyGeneratorPlugins, array $searchSyncKeyGeneratorPlugins)
    {
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

        if ($keyGeneratorPlugin instanceof AbstractKeyGenerator) {
            $keyGeneratorPlugin->setResource($resourceName);
        }

        return $keyGeneratorPlugin;
    }

}
