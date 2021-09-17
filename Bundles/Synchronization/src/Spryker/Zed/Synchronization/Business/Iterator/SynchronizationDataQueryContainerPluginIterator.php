<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Iterator;

use Generated\Shared\Transfer\SynchronizationDataQueryExpanderStrategyConfigurationTransfer;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryExpanderStrategyPluginInterface;

class SynchronizationDataQueryContainerPluginIterator extends AbstractSynchronizationDataPluginIterator
{
    /**
     * @var \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface
     */
    protected $plugin;

    /**
     * @var \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryExpanderStrategyPluginInterface
     */
    protected $synchronizationDataQueryExpanderStrategyPlugin;

    /**
     * @var array<int>
     */
    protected $filterIds;

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface $plugin
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryExpanderStrategyPluginInterface $synchronizationDataQueryExpanderStrategyPlugin
     * @param int $chunkSize
     * @param array<int> $ids
     */
    public function __construct(
        SynchronizationDataQueryContainerPluginInterface $plugin,
        SynchronizationDataQueryExpanderStrategyPluginInterface $synchronizationDataQueryExpanderStrategyPlugin,
        int $chunkSize,
        array $ids = []
    ) {
        parent::__construct($plugin, $chunkSize);

        $this->synchronizationDataQueryExpanderStrategyPlugin = $synchronizationDataQueryExpanderStrategyPlugin;
        $this->filterIds = $ids;
    }

    /**
     * @return void
     */
    protected function updateCurrent(): void
    {
        $synchronizationDataQueryExpanderStrategyConfigurationTransfer = new SynchronizationDataQueryExpanderStrategyConfigurationTransfer();
        $synchronizationDataQueryExpanderStrategyConfigurationTransfer
            ->setOffset($this->offset)
            ->setChunkSize($this->chunkSize);

        $query = $this->plugin->queryData($this->filterIds);

        $query = $this->synchronizationDataQueryExpanderStrategyPlugin->expandQuery(
            $query,
            $synchronizationDataQueryExpanderStrategyConfigurationTransfer
        );

        $this->current = $query->find()->getData();
    }
}
