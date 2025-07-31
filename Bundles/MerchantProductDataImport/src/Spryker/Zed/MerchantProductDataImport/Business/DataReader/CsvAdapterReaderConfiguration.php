<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\DataReader;

use Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfiguration;

class CsvAdapterReaderConfiguration extends CsvReaderConfiguration
{
    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->dataImporterReaderConfigurationTransfer->getFileNameOrFail();
    }
}
