<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Helper;

use Codeception\Module;
use Codeception\Stub\Expected;
use Codeception\Util\Stub;
use Faker\Factory;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface;
use Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportInterface;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;

class DataImporterHelper extends Module
{
    /**
     * @param string $importType
     * @param bool $isCalled
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer|null $dataImporterReportTransfer
     *
     * @return object|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getDataImporterMock($importType, $isCalled = false, ?DataImporterReportTransfer $dataImporterReportTransfer = null)
    {
        if (!$dataImporterReportTransfer) {
            $dataImporterReportTransfer = new DataImporterReportTransfer();
            $dataImporterReportTransfer->setImportType($importType)
                ->setImportedDataSetCount(0);
        }

        $dataImporterStub = Stub::makeEmpty(DataImporterInterface::class, [
            'import' => Expected::exactly(($isCalled ? 1 : 0), function () use ($dataImporterReportTransfer) {
                return $dataImporterReportTransfer;
            }),
            'getImportType' => function () use ($importType) {
                return $importType;
            },
        ]);

        return $dataImporterStub;
    }

    /**
     * @param string $importType
     * @param bool $isCalled
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer|null $dataImporterReportTransfer
     *
     * @return object|\Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface
     */
    public function getDataImporterPluginMock($importType, $isCalled = false, ?DataImporterReportTransfer $dataImporterReportTransfer = null)
    {
        if (!$dataImporterReportTransfer) {
            $dataImporterReportTransfer = new DataImporterReportTransfer();
            $dataImporterReportTransfer->setImportType($importType)
                ->setImportedDataSetCount(0);
        }

        $dataImporterPluginStub = Stub::makeEmpty(DataImportPluginInterface::class, [
            'import' => Expected::exactly(($isCalled ? 1 : 0), function () use ($dataImporterReportTransfer) {
                return $dataImporterReportTransfer;
            }),
            'getImportType' => function () use ($importType) {
                return $importType;
            },
        ]);

        return $dataImporterPluginStub;
    }

    /**
     * @return object|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function getDataImportStepMock()
    {
        $dataSetStub = Stub::makeEmpty(DataImportStepInterface::class);

        return $dataSetStub;
    }

    /**
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return object|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function getFailingDataImportStepMock()
    {
        $executeCallback = function () {
            throw new DataImportException();
        };
        $dataSetStub = Stub::makeEmpty(DataImportStepInterface::class, ['execute' => $executeCallback]);

        return $dataSetStub;
    }

    /**
     * @return object|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface
     */
    public function getDataSetMock()
    {
        $dataSetStub = Stub::makeEmpty(DataSetStepBrokerInterface::class);

        return $dataSetStub;
    }

    /**
     * @return object|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportInterface
     */
    public function getBeforeImportHookMock()
    {
        $beforeHook = Stub::makeEmpty(DataImporterBeforeImportInterface::class, [
            'beforeImport' => Expected::exactly(1),
        ]);

        return $beforeHook;
    }

    /**
     * @return object|\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface
     */
    public function getAfterImportHookMock()
    {
        $beforeHook = Stub::makeEmpty(DataImporterAfterImportInterface::class, [
            'afterImport' => Expected::exactly(1),
        ]);

        return $beforeHook;
    }

    /**
     * @param array|null $dataSets
     * @param int $numberOfDataSets
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
     */
    public function getDataReader(?array $dataSets = null, $numberOfDataSets = 3)
    {
        if (!$dataSets) {
            $faker = Factory::create();
            $keys = ['column1', 'column2', 'column3'];

            $dataSets = [];
            for ($i = 0; $i < $numberOfDataSets; $i++) {
                $dataSets[] = new DataSet(array_combine($keys, [$faker->word, $faker->randomDigitNotNull, $faker->text]));
            }
        }

        return new DataReaderStub($dataSets);
    }
}
