<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Generated\Shared\Transfer\DataExportResultTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;
use Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\DataReaderInterface;
use Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToDataExportServiceInterface;
use Spryker\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportConfig;

class MerchantSalesOrderDataExporter implements MerchantSalesOrderDataExporterInterface
{
    protected const LIMIT_VALUE = 200;

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportRepository::FILTER_CRITERIA_PARAM_OFFSET
     */
    protected const FILTER_CRITERIA_KEY_OFFSET = 'offset';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportRepository::FILTER_CRITERIA_PARAM_LIMIT
     */
    protected const FILTER_CRITERIA_KEY_LIMIT = 'limit';

    protected const HOOK_KEY_MERCHANT_NAME = 'merchant_name';
    protected const HOOK_KEY_STORE_NAME = 'store_name';

    /**
     * @uses \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesOrderMapper::KEY_MERCHANT_NAME
     */
    protected const EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME = 'merchant_name';
    protected const EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE = 'merchant_order_store';

    protected const DATA_ENTITY_MERCHANT_ORDER = 'merchant-order';

    /**
     * @var \Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToDataExportServiceInterface
     */
    protected $dataExportService;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportConfig
     */
    protected $merchantSalesOrderDataExportConfig;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\DataReaderInterface
     */
    protected $dataReader;

    /**
     * @var string
     */
    protected $merchantName;

    /**
     * @var string
     */
    protected $storeName;

    /**
     * @param \Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToDataExportServiceInterface $dataExportService
     * @param \Spryker\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportConfig $merchantSalesOrderDataExportConfig
     * @param \Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\DataReaderInterface $dataReader
     */
    public function __construct(
        MerchantSalesOrderDataExportToDataExportServiceInterface $dataExportService,
        MerchantSalesOrderDataExportConfig $merchantSalesOrderDataExportConfig,
        DataReaderInterface $dataReader
    ) {
        $this->dataExportService = $dataExportService;
        $this->merchantSalesOrderDataExportConfig = $merchantSalesOrderDataExportConfig;
        $this->dataReader = $dataReader;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function export(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        $dataExportConfigurationTransfer = $this->resolveDataExportActionConfigurationTransfer($dataExportConfigurationTransfer);
        $extendedDataExportConfigurationTransfer = $this->extendDataExportConfigurationTransfer($dataExportConfigurationTransfer);
        $dataExportResultTransfer = $this->createDataExportResultTransfer($dataExportConfigurationTransfer);
        do {
            $dataExportBatchTransfer = $this->dataReader->readBatch($extendedDataExportConfigurationTransfer);

            if (!($dataExportBatchTransfer->getData())) {
                return $this->createDataExportReportTransfer($dataExportResultTransfer);
            }
            $dataExportResultTransfer = $this->exportBatchData(
                $dataExportBatchTransfer,
                $dataExportConfigurationTransfer,
                $dataExportResultTransfer
            );

            if (!$dataExportResultTransfer->getIsSuccessful()) {
                return $this->createDataExportReportTransfer($dataExportResultTransfer);
            }

            $extendedDataExportConfigurationTransfer->addFilterCriterion(
                static::FILTER_CRITERIA_KEY_OFFSET,
                $dataExportResultTransfer->getExportCount()
            );
        } while ($dataExportResultTransfer->getExportCount() === static::LIMIT_VALUE);

        return $this->createDataExportReportTransfer($dataExportResultTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportBatchTransfer $dataExportBatchTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportResultTransfer
     */
    protected function exportBatchData(
        DataExportBatchTransfer $dataExportBatchTransfer,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer,
        DataExportResultTransfer $dataExportResultTransfer
    ): DataExportResultTransfer {
        $dataExportBatchData = $dataExportBatchTransfer->getData();
        $exportData = [];

        if ($dataExportBatchTransfer->getOffset() === 0) {
            $this->merchantName = $dataExportBatchData[0][static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME];
            $this->storeName = $dataExportBatchData[0][static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE];
        }
        $exportedRowsCount = $dataExportBatchTransfer->getOffset() ? $dataExportBatchTransfer->getOffset() : 0;

        foreach ($dataExportBatchData as $dataExportRow) {
            $exportedRowsCount++;
            if (
                $dataExportRow[static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME] === $this->merchantName
                && $dataExportRow[static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE] === $this->storeName
            ) {
                $exportData[] = $dataExportRow;

                continue;
            }

            $dataExportWriteResponseTransfer = $this->writeMerchantStoreData(
                $exportData,
                $dataExportConfigurationTransfer,
                $dataExportBatchTransfer
            );

            if (!$dataExportWriteResponseTransfer->getIsSuccessful()) {
                return $dataExportResultTransfer
                    ->fromArray($dataExportWriteResponseTransfer->toArray(), true)
                    ->setExportCount($exportedRowsCount);
            }

            $this->merchantName = $dataExportRow[static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME];
            $this->storeName = $dataExportRow[static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE];
            $exportData = [];
            $exportData[] = $dataExportRow;
        }

        $dataExportWriteResponseTransfer = $this->writeMerchantStoreData(
            $exportData,
            $dataExportConfigurationTransfer,
            $dataExportBatchTransfer
        );

        if (!$dataExportWriteResponseTransfer->getIsSuccessful()) {
            return $dataExportResultTransfer
                ->fromArray($dataExportWriteResponseTransfer->toArray(), true)
                ->setExportCount($exportedRowsCount);
        }

        return $dataExportResultTransfer
            ->setIsSuccessful(true)
            ->setExportCount($exportedRowsCount)
            ->setFileName($dataExportWriteResponseTransfer->getFileName());
    }

    /**
     * @param mixed[] $exportData
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportBatchTransfer $dataExportBatchTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportWriteResponseTransfer
     */
    protected function writeMerchantStoreData(
        array $exportData,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer,
        DataExportBatchTransfer $dataExportBatchTransfer
    ): DataExportWriteResponseTransfer {
        $dataExportConfigurationTransfer->addHook(
            static::HOOK_KEY_MERCHANT_NAME,
            $exportData[0][static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME]
        );
        $dataExportConfigurationTransfer->addHook(
            static::HOOK_KEY_STORE_NAME,
            $exportData[0][static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE]
        );

        $dataExportBatchTransfer->setData($exportData);
        $dataExportBatchTransfer = $this->removeConfigurationFields($dataExportBatchTransfer, $dataExportConfigurationTransfer->getDataEntity());

        return $this->dataExportService->write(
            $dataExportBatchTransfer,
            $dataExportConfigurationTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    protected function createDataExportReportTransfer(DataExportResultTransfer $dataExportResultTransfer): DataExportReportTransfer
    {
        return (new DataExportReportTransfer())
            ->setIsSuccessful($dataExportResultTransfer->getIsSuccessful())
            ->addDataExportResult($dataExportResultTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportResultTransfer
     */
    protected function createDataExportResultTransfer(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportResultTransfer {
        return (new DataExportResultTransfer())
            ->setDataEntity($dataExportConfigurationTransfer->getDataEntity())
            ->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    protected function resolveDataExportActionConfigurationTransfer(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportConfigurationTransfer {
        $salesDataExportDataExportConfigurationsTransfer = $this->dataExportService->parseConfiguration(
            $this->merchantSalesOrderDataExportConfig->getModuleDataExportConfigurationsFilePath()
        );
        $dataExportConfigurationTransfer = $this->dataExportService->resolveDataExportActionConfiguration(
            $dataExportConfigurationTransfer,
            $salesDataExportDataExportConfigurationsTransfer
        );

        return $dataExportConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportBatchTransfer $dataExportBatchTransfer
     * @param string|null $dataEntity
     *
     * @return \Generated\Shared\Transfer\DataExportBatchTransfer
     */
    protected function removeConfigurationFields(DataExportBatchTransfer $dataExportBatchTransfer, ?string $dataEntity): DataExportBatchTransfer
    {
        $data = $dataExportBatchTransfer->getData();
        foreach ($data as $dataKey => $dataRow) {
            unset($dataRow[static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME]);
            if ($dataEntity !== static::DATA_ENTITY_MERCHANT_ORDER) {
                unset($dataRow[static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE]);
            }
            $data[$dataKey] = $dataRow;
        }
        $fields = $dataExportBatchTransfer->getFields();
        foreach ($fields as $key => $field) {
            if (
                $field === static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME ||
                ($field === static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE &&
                    $dataEntity !== static::DATA_ENTITY_MERCHANT_ORDER)
            ) {
                unset($fields[$key]);
            }
        }

        return $dataExportBatchTransfer->setData($data)->setFields($fields);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    protected function extendDataExportConfigurationTransfer(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportConfigurationTransfer {
        $extendedDataExportConfigurationTransfer = clone $dataExportConfigurationTransfer;

        return $extendedDataExportConfigurationTransfer
            ->addFilterCriterion(static::FILTER_CRITERIA_KEY_OFFSET, 0)
            ->addFilterCriterion(static::FILTER_CRITERIA_KEY_LIMIT, static::LIMIT_VALUE)
            ->addField(static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME)
            ->addField(static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE);
    }
}
