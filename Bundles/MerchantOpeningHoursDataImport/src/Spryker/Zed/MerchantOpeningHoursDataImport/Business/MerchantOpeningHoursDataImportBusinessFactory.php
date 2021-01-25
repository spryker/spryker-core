<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOpeningHoursDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step\DateScheduleWriterStep;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step\MerchantKeyToIdMerchantStep;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step\MerchantOpeningHoursDateScheduleWriterStep;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step\MerchantOpeningHoursWeekdayScheduleWriterStep;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step\WeekdayScheduleWriterStep;

/**
 * @method \Spryker\Zed\MerchantOpeningHoursDataImport\MerchantOpeningHoursDataImportConfig getConfig()
 */
class MerchantOpeningHoursDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantOpeningHoursWeekdayScheduleDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantOpeningHoursWeekdayScheduleDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createMerchantKeyToIdMerchantStep())
            ->addStep($this->createWeekdayScheduleWriterStep())
            ->addStep($this->createMerchantOpeningHoursWeekdayScheduleWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getMerchantOpeningHoursDateScheduleDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantOpeningHoursDateScheduleDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createMerchantKeyToIdMerchantStep())
            ->addStep($this->createDateScheduleWriterStep())
            ->addStep($this->createMerchantOpeningHoursDateScheduleWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createMerchantKeyToIdMerchantStep(): DataImportStepInterface
    {
        return new MerchantKeyToIdMerchantStep();
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
