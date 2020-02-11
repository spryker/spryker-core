<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataSetItemTransfer;
use Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetItemWriterPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DataImport\Business\DataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\DataImport\DataImportConfig getConfig()
 */
abstract class AbstractQueueWriterPlugin extends AbstractPlugin implements DataSetItemWriterPluginInterface
{
    /**
     * @var \Generated\Shared\Transfer\DataSetItemTransfer[]
     */
    protected static $dataSetItemBuffer = [];

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataSetItemTransfer $dataSetItemTransfer
     *
     * @return void
     */
    public function write(DataSetItemTransfer $dataSetItemTransfer): void
    {
        $this->collectQueueSendMessage($dataSetItemTransfer);

        if (count(static::$dataSetItemBuffer) >= $this->getChunkSize()) {
            $this->flush();
        }
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function flush(): void
    {
        $this->getFacade()->writeDataSetItemsToQueue($this->getQueueName(), static::$dataSetItemBuffer);

        static::$dataSetItemBuffer = [];
    }

    /**
     * Returns the name of the queue data should be written to.
     *
     * @return string
     */
    abstract protected function getQueueName(): string;

    /**
     * Returns the size of the chunk in which data should be written to the queue.
     *
     * @return int
     */
    abstract protected function getChunkSize(): int;

    /**
     * @param \Generated\Shared\Transfer\DataSetItemTransfer $dataSetItemTransfer
     *
     * @return void
     */
    protected function collectQueueSendMessage(DataSetItemTransfer $dataSetItemTransfer): void
    {
        static::$dataSetItemBuffer[] = $dataSetItemTransfer;
    }
}
