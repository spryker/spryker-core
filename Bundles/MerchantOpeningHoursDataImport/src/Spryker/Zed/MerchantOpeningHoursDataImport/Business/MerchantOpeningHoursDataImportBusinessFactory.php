<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOpeningHoursDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step\DateScheduleWriterStep;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step\MerchantOpeningHoursDateScheduleWriterStep;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step\MerchantOpeningHoursWeekdayScheduleWriterStep;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step\MerchantReferenceToIdMerchantStep;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step\WeekdayScheduleWriterStep;

/**
 * @method \Spryker\Zed\MerchantOpeningHoursDataImport\MerchantOpeningHoursDataImportConfig getConfig()
 */
class MerchantOpeningHoursDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantOpeningHoursWeekdayScheduleDataImporter(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?: $this->getConfig()->getMerchantOpeningHoursWeekdayScheduleDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createMerchantReferenceToIdMerchantStep())
            ->addStep($this->createWeekdayScheduleWriterStep())
            ->addStep($this->createMerchantOpeningHoursWeekdayScheduleWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantOpeningHoursDateScheduleDataImporter(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterInterface {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?: $this->getConfig()->getMerchantOpeningHoursDateScheduleDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createMerchantReferenceToIdMerchantStep())
            ->addStep($this->createDateScheduleWriterStep())
            ->addStep($this->createMerchantOpeningHoursDateScheduleWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantReferenceToIdMerchantStep(): DataImportStepInterface
    {
        return new MerchantReferenceToIdMerchantStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createWeekdayScheduleWriterStep(): DataImportStepInterface
    {
        return new WeekdayScheduleWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createDateScheduleWriterStep(): DataImportStepInterface
    {
        return new DateScheduleWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantOpeningHoursWeekdayScheduleWriterStep(): DataImportStepInterface
    {
        return new MerchantOpeningHoursWeekdayScheduleWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantOpeningHoursDateScheduleWriterStep(): DataImportStepInterface
    {
        return new MerchantOpeningHoursDateScheduleWriterStep();
    }
}
