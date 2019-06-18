<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Iterator;

use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

class SynchronizationDataRepositoryPluginIterator extends AbstractSynchronizationDataPluginIterator
{
    /**
     * @var \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface
     */
    protected $plugin;

    /**
     * @var \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    protected $data;

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface $plugin
     * @param int $chunkSize
     * @param int[] $ids
     */
    public function __construct(SynchronizationDataRepositoryPluginInterface $plugin, int $chunkSize, array $ids = [])
    {
        parent::__construct($plugin, $chunkSize);

        $this->data = $plugin->getData($ids);
    }

    /**
     * @return void
     */
    protected function updateCurrent(): void
    {
        $this->current = array_slice($this->data, $this->offset, $this->chunkSize, true);
    }
}
