<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Iterator;

use Iterator;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface;

abstract class AbstractSynchronizationDataPluginIterator implements Iterator
{
    /**
     * @var \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface
     */
    protected $plugin;

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * @var array
     */
    protected $current = [];

    /**
     * @var int
     */
    protected $chunkSize;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface $plugin
     * @param int $chunkSize
     */
    public function __construct(SynchronizationDataPluginInterface $plugin, int $chunkSize)
    {
        $this->plugin = $plugin;
        $this->chunkSize = $chunkSize;
    }

    /**
     * @return void
     */
    abstract protected function updateCurrent(): void;

    /**
     * @return array
     */
    public function current(): array
    {
        return $this->current;
    }

    /**
     * @return void
     */
    public function next(): void
    {
        $this->offset += $this->chunkSize;
        $this->index += 1;
        $this->updateCurrent();
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return is_array($this->current) && $this->current !== [];
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->offset = 0;
        $this->index = 0;
        $this->updateCurrent();
    }
}
