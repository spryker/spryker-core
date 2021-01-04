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
use Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportRepository;

class MerchantSalesOrderDataExporter implements MerchantSalesOrderDataExporterInterface
{
    protected const LIMIT_VALUE = 200;

    protected const HOOK_KEY_MERCHANT_NAME = 'merchant_name';
    protected const HOOK_KEY_STORE_NAME = 'store_name';

    protected const  EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME = 'merchant_name';
    protected const  EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE = 'merchant_order_store';

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

        $dataExportResultTransfer = $this->createDataExportResultTransfer($dataExportConfigurationTransfer);

        $extendedDataExportConfigurationTransfer = $this->getExtendedDataExportConfigurationTransfer($dataExportConfigurationTransfer);
        $extendedDataExportConfigurationTransfer
            ->addFilterCriterion(MerchantSalesOrderDataExportRepository::FILTER_CRITERIA_PARAM_OFFSET, 0)
            ->addFilterCriterion(MerchantSalesOrderDataExportRepository::FILTER_CRITERIA_PARAM_LIMIT, static::LIMIT_VALUE);
        $dataExportBatchTransfer = $this->dataReader->readBatch($extendedDataExportConfigurationTransfer);
        $dataExportBatchData = $dataExportBatchTransfer->getData();

        if (!$dataExportBatchData) {
            return $this->createDataExportReportTransfer($dataExportResultTransfer);
        }
        $merchantName = $dataExportBatchData[0][static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME];
        $storeName = $dataExportBatchData[0][static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE];
        $exportData = [];
        $exportedRawsCount = 0;

        while (!empty($dataExportBatchData)) :
            foreach ($dataExportBatchData as $dataExportRow) {
                $exportedRawsCount++;
                if (
                    $dataExportRow[static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME] === $merchantName
                    && $dataExportRow[static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE] === $storeName
                ) {
                    $exportData[] = $dataExportRow;

                    continue;
                }
                $dataExportWriteResponseTransfer = $this->writeMerchantStoreData(
                    $exportData,
                    $dataExportConfigurationTransfer,
                    $dataExportBatchTransfer
                );

                $merchantName = $dataExportRow[static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME];
                $storeName = $dataExportRow[static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE];
                $exportData = [];
                $exportData[] = $dataExportRow;
            }
            $extendedDataExportConfigurationTransfer->addFilterCriterion(
                MerchantSalesOrderDataExportRepository::FILTER_CRITERIA_PARAM_OFFSET,
                $exportedRawsCount
            );
            $dataExportBatchTransfer = $this->dataReader->readBatch($extendedDataExportConfigurationTransfer);
            $dataExportBatchData = $dataExportBatchTransfer->getData();
        endwhile;

        if (!empty($exportData)) {
            $dataExportWriteResponseTransfer = $this->writeMerchantStoreData(
                $exportData,
                $dataExportConfigurationTransfer,
                $dataExportBatchTransfer
            );
        }

        if (isset($dataExportWriteResponseTransfer) && !$dataExportWriteResponseTransfer->getIsSuccessful()) {
            $dataExportResultTransfer
                ->fromArray($dataExportWriteResponseTransfer->toArray(), true)
                ->setExportCount($exportedRawsCount);

            return $this->createDataExportReportTransfer($dataExportResultTransfer);
        }

        if (isset($dataExportWriteResponseTransfer)) {
            $dataExportResultTransfer
                ->setIsSuccessful(true)
                ->setExportCount($exportedRawsCount)
                ->setFileName($dataExportWriteResponseTransfer->getFileName());
        }

        return $this->createDataExportReportTransfer($dataExportResultTransfer);
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
        $dataExportBatchTransfer->setOffset(0);
        $dataExportBatchTransfer = $this->trimData($dataExportBatchTransfer, $dataExportConfigurationTransfer->getDataEntity());

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
    protected function trimData(DataExportBatchTransfer $dataExportBatchTransfer, ?string $dataEntity): DataExportBatchTransfer
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
    protected function getExtendedDataExportConfigurationTransfer(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportConfigurationTransfer {
        $extendedDataExportConfigurationTransfer = clone $dataExportConfigurationTransfer;

        return $extendedDataExportConfigurationTransfer
            ->addField(static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_NAME)
            ->addField(static::EXTENDED_DATA_EXPORT_CONFIGURATION_FIELD_MERCHANT_ORDER_STORE);
    }
}
