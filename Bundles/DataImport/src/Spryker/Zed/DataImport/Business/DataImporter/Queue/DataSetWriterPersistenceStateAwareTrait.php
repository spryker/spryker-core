<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataImporter\Queue;

trait DataSetWriterPersistenceStateAwareTrait
{
    /**
     * @return bool
     */
    protected function isDataSetWriterDataPersisted(): bool
    {
        return DataSetWriterPersistenceStateRegistry::getIsPersisted();
    }

    /**
     * @param bool $isPersisted
     *
     * @return void
     */
    protected function setDataSetWriterPersistenceState(bool $isPersisted): void
    {
        DataSetWriterPersistenceStateRegistry::setIsPersisted($isPersisted);
    }
}
