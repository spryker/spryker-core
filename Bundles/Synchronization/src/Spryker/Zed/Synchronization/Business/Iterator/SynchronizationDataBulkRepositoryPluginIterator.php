<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Iterator;

use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

class SynchronizationDataBulkRepositoryPluginIterator extends AbstractSynchronizationDataPluginIterator
{
    /**
     * @var \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface
     */
    protected $plugin;

    /**
     * @var int[]
     */
    protected $filterIds;

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface $plugin
     * @param int $chunkSize
     * @param int[] $ids
     */
    public function __construct(SynchronizationDataBulkRepositoryPluginInterface $plugin, int $chunkSize, array $ids = [])
    {
        parent::__construct($plugin, $chunkSize);

        $this->filterIds = $ids;
    }

    /**
     * @return void
     */
    protected function updateCurrent(): void
    {
        $this->current = $this->plugin->getData($this->offset, $this->chunkSize, $this->filterIds);
    }
}
