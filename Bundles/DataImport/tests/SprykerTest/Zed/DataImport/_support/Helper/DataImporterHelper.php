<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Helper;

use Codeception\Module;
use Codeception\Stub\Expected;
use Codeception\Util\Stub;
use Faker\Factory;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Model\DataImporter;
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
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getDataImporterMock($importType, $isCalled = false, ?DataImporterReportTransfer $dataImporterReportTransfer = null)
    {
        if (!$dataImporterReportTransfer) {
            $dataImporterReportTransfer = new DataImporterReportTransfer();
            $dataImporterReportTransfer->setImportType($importType)
                ->setImportedDataSetCount(0);
        }

        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporterInterface $dataImporterStub */
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
     * @param string $importGroup
     * @param bool $isCalled
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer|null $dataImporterReportTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\DataImporter\DataImporterImportGroupAwareInterface
     */
    public function getDataImporterImportGroupAwareMock(string $importType, string $importGroup, bool $isCalled = false, ?DataImporterReportTransfer $dataImporterReportTransfer = null)
    {
        if (!$dataImporterReportTransfer) {
            $dataImporterReportTransfer = new DataImporterReportTransfer();
            $dataImporterReportTransfer->setImportType($importType)
                ->setImportedDataSetCount(0);
        }

        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporterStub */
        $dataImporterStub = Stub::makeEmpty(DataImporter::class, [
            'import' => Expected::exactly(($isCalled ? 1 : 0), function () use ($dataImporterReportTransfer) {
                return $dataImporterReportTransfer;
            }),
            'getImportType' => function () use ($importType) {
                return $importType;
            },
            'getImportGroup' => function () use ($importGroup) {
                return $importGroup;
            },
        ]);

        return $dataImporterStub;
    }

    /**
     * @param string $importType
     * @param bool $isCalled
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer|null $dataImporterReportTransfer
     *
     * @return \Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface
     */
    public function getDataImporterPluginMock($importType, $isCalled = false, ?DataImporterReportTransfer $dataImporterReportTransfer = null)
    {
        if (!$dataImporterReportTransfer) {
            $dataImporterReportTransfer = new DataImporterReportTransfer();
            $dataImporterReportTransfer->setImportType($importType)
                ->setImportedDataSetCount(0);
        }

        /** @var \Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface $dataImporterPluginStub */
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
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function getDataImportStepMock()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface $dataSetStub */
        $dataSetStub = Stub::makeEmpty(DataImportStepInterface::class);

        return $dataSetStub;
    }

    /**
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function getFailingDataImportStepMock()
    {
        $executeCallback = function () {
            throw new DataImportException('ExceptionMessage');
        };
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface $dataSetStub */
        $dataSetStub = Stub::makeEmpty(DataImportStepInterface::class, ['execute' => $executeCallback]);

        return $dataSetStub;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface
     */
    public function getDataSetMock()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerInterface $dataSetStub */
        $dataSetStub = Stub::makeEmpty(DataSetStepBrokerInterface::class);

        return $dataSetStub;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportInterface
     */
    public function getBeforeImportHookMock()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportInterface $beforeHook */
        $beforeHook = Stub::makeEmpty(DataImporterBeforeImportInterface::class, [
            'beforeImport' => Expected::exactly(1),
        ]);

        return $beforeHook;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface
     */
    public function getAfterImportHookMock()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface $beforeHook */
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
