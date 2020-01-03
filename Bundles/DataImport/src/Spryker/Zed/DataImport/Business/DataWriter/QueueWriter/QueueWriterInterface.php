<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataWriter\QueueWriter;

interface QueueWriterInterface
{
    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\DataSetItemTransfer[] $dataSetItemTransfers
     *
     * @return void
     */
    public function write(string $queueName, array $dataSetItemTransfers): void;
}
