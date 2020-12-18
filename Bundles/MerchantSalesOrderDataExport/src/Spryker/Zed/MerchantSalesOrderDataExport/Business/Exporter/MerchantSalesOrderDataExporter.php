<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Generated\Shared\Transfer\DataExportResultTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\MerchantSalesOrderDataReaderInterface;
use Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToDataExportServiceInterface;
use Spryker\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportConfig;

class MerchantSalesOrderDataExporter implements MerchantSalesOrderDataExporterInterface
{
    public const FILTER_CRITERIA_KEY_MERCHANT_NAME = 'merchant_name';
    public const FILTER_CRITERIA_KEY_STORE_NAME = 'store_name';
    public const FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT = 'merchant_order_created_at';
    public const FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT = 'merchant_order_updated_at';
    public const FILTER_CRITERIA_PARAM_DATE_FROM = 'from';
    public const FILTER_CRITERIA_PARAM_DATE_TO = 'to';

    protected const READ_BATCH_SIZE = 100;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToDataExportServiceInterface
     */
    protected $dataExportService;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportConfig
     */
    protected $salesDataExportConfig;

    /**
     * @var \Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\MerchantSalesOrderDataReaderInterface
     */
    protected $dataReader;

    /**
     * @param \Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToDataExportServiceInterface $dataExportService
     * @param \Spryker\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportConfig $salesDataExportConfig
     * @param \Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\MerchantSalesOrderDataReaderInterface $dataReader
     */
    public function __construct(
        MerchantSalesOrderDataExportToDataExportServiceInterface $dataExportService,
        MerchantSalesOrderDataExportConfig $salesDataExportConfig,
        MerchantSalesOrderDataReaderInterface $dataReader
    ) {
        $this->dataExportService = $dataExportService;
        $this->salesDataExportConfig = $salesDataExportConfig;
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

        $filterCriteria = $dataExportConfigurationTransfer->getFilterCriteria();

        $dataExportResultTransfer = (new DataExportResultTransfer())
            ->setDataEntity($dataExportConfigurationTransfer->getDataEntity())
            ->setIsSuccessful(false);

        $merchantNames = SpyMerchantQuery::create()
            ->select([SpyMerchantTableMap::COL_NAME])
            ->find()
            ->toArray();

        $totalExportCount = 0;

        $dataExportConfigurationTransfer->addFilterCriterion(
            static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT,
            $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_CREATED_AT]
        );
        $dataExportConfigurationTransfer->addFilterCriterion(
            static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT,
            $filterCriteria[static::FILTER_CRITERIA_KEY_MERCHANT_ORDER_UPDATED_AT]
        );

        foreach ($merchantNames as $merchantName) {
            $dataExportConfigurationTransfer->addFilterCriterion(
                static::FILTER_CRITERIA_KEY_MERCHANT_NAME,
                $merchantName
            );

            $storeNames = $filterCriteria[static::FILTER_CRITERIA_KEY_STORE_NAME];

            foreach ($storeNames as $storeName) {
                $dataExportConfigurationTransfer->addFilterCriterion(
                    static::FILTER_CRITERIA_KEY_STORE_NAME,
                    $storeNames
                );
                $offset = 0;
                do {
                    $dataExportConfigurationTransfer->addHook(static::FILTER_CRITERIA_KEY_MERCHANT_NAME, $merchantName);
                    $dataExportConfigurationTransfer->addHook(static::FILTER_CRITERIA_KEY_STORE_NAME, $storeName);
                    $dataExportBatchTransfer = $this->dataReader->readBatch($dataExportConfigurationTransfer, $offset, static::READ_BATCH_SIZE);
                    $dataExportWriteResponseTransfer = $this->dataExportService->write($dataExportBatchTransfer, $dataExportConfigurationTransfer);

                    if (!$dataExportWriteResponseTransfer->getIsSuccessful()) {
                        $dataExportResultTransfer
                            ->fromArray($dataExportWriteResponseTransfer->toArray(), true)
                            ->setExportCount($offset);

                        return $this->createDataExportReportTransfer($dataExportResultTransfer);
                    }

                    $exportedRowCount = count($dataExportBatchTransfer->getData());
                    $offset += $exportedRowCount;
                    $totalExportCount += $exportedRowCount;

                    $dataExportResultTransfer
                        ->setIsSuccessful(true)
                        ->setExportCount($totalExportCount)
                        ->setFileName($dataExportWriteResponseTransfer->getFileName());
                } while ($exportedRowCount === static::READ_BATCH_SIZE);
            }
        }

        return $this->createDataExportReportTransfer($dataExportResultTransfer);
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
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    protected function resolveDataExportActionConfigurationTransfer(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportConfigurationTransfer {
        $salesDataExportDataExportConfigurationsTransfer = $this->dataExportService->parseConfiguration(
            $this->salesDataExportConfig->getModuleDataExportConfigurationsFilePath()
        );
        $dataExportConfigurationTransfer = $this->dataExportService->resolveDataExportActionConfiguration(
            $dataExportConfigurationTransfer,
            $salesDataExportDataExportConfigurationsTransfer
        );

        return $dataExportConfigurationTransfer;
    }
}
