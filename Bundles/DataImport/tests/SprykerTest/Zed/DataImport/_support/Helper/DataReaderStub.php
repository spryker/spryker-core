<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Helper;

use ArrayIterator;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Model\DataReader\ConfigurableDataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;

class DataReaderStub extends ArrayIterator implements DataReaderInterface, ConfigurableDataReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer $dataImportReaderConfigurationTransfer
     *
     * @return $this
     */
    public function configure(DataImporterReaderConfigurationTransfer $dataImportReaderConfigurationTransfer)
    {
        return $this;
    }
}
