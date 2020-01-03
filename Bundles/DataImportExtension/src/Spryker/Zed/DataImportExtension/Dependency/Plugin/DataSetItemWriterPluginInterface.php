<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DataSetItemTransfer;

interface DataSetItemWriterPluginInterface
{
    /**
     * Specification:
     * - Writes the data set payload to a destination determined by implementation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataSetItemTransfer $dataSetItemTransfer
     *
     * @return void
     */
    public function write(DataSetItemTransfer $dataSetItemTransfer): void;

    /**
     * Specification:
     * - Flushes the unwritten data.
     *
     * @api
     *
     * @return void
     */
    public function flush(): void;
}
