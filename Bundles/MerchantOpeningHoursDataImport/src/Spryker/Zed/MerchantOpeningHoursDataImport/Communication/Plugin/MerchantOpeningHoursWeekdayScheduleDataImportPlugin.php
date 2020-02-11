<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOpeningHoursDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantOpeningHoursDataImport\MerchantOpeningHoursDataImportConfig;

/**
 * @method \Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHoursDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantOpeningHoursDataImport\MerchantOpeningHoursDataImportConfig getConfig()
 */
class MerchantOpeningHoursWeekdayScheduleDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer {
        return $this->getFacade()->importMerchantOpeningHoursWeekdaySchedule($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return MerchantOpeningHoursDataImportConfig::IMPORT_TYPE_MERCHANT_OPENING_HOURS_WEEKDAY_SCHEDULE;
    }
}
