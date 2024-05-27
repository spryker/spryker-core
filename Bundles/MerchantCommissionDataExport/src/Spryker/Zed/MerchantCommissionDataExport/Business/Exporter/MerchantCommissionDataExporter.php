<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Zed\MerchantCommissionDataExport\Dependency\Service\MerchantCommissionDataExportToDataExportServiceInterface;
use Spryker\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportConfig;
use Spryker\Zed\MerchantCommissionDataExport\Persistence\MerchantCommissionDataExportRepositoryInterface;

class MerchantCommissionDataExporter implements MerchantCommissionDataExporterInterface
{
    /**
     * @var string
     */
    protected const FILTER_CRITERIA_KEY_OFFSET = 'offset';

    /**
     * @var string
     */
    protected const FILTER_CRITERIA_KEY_LIMIT = 'limit';

    /**
     * @var \Spryker\Zed\MerchantCommissionDataExport\Persistence\MerchantCommissionDataExportRepositoryInterface
     */
    protected MerchantCommissionDataExportRepositoryInterface $merchantCommissionDataExportRepository;

    /**
     * @var \Spryker\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportConfig
     */
    protected MerchantCommissionDataExportConfig $merchantCommissionDataExportConfig;

    /**
     * @var \Spryker\Zed\MerchantCommissionDataExport\Dependency\Service\MerchantCommissionDataExportToDataExportServiceInterface
     */
    protected MerchantCommissionDataExportToDataExportServiceInterface $dataExportService;

    /**
     * @param \Spryker\Zed\MerchantCommissionDataExport\Persistence\MerchantCommissionDataExportRepositoryInterface $merchantCommissionDataExportRepository
     * @param \Spryker\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportConfig $merchantCommissionDataExportConfig
     * @param \Spryker\Zed\MerchantCommissionDataExport\Dependency\Service\MerchantCommissionDataExportToDataExportServiceInterface $dataExportService
     */
    public function __construct(
        MerchantCommissionDataExportRepositoryInterface $merchantCommissionDataExportRepository,
        MerchantCommissionDataExportConfig $merchantCommissionDataExportConfig,
        MerchantCommissionDataExportToDataExportServiceInterface $dataExportService
    ) {
        $this->merchantCommissionDataExportRepository = $merchantCommissionDataExportRepository;
        $this->merchantCommissionDataExportConfig = $merchantCommissionDataExportConfig;
        $this->dataExportService = $dataExportService;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function export(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        $dataExportResultTransfer = (new DataExportResultTransfer())->setIsSuccessful(false);

        $offset = 0;
        $totalExportedCount = 0;
        $limit = $this->merchantCommissionDataExportConfig->getMerchantCommissionReadBatchSize();
        do {
            $dataExportConfigurationTransfer
                ->addFilterCriterion(static::FILTER_CRITERIA_KEY_OFFSET, $offset)
                ->addFilterCriterion(static::FILTER_CRITERIA_KEY_LIMIT, $limit);
            $dataExportBatchTransfer = $this->merchantCommissionDataExportRepository->getMerchantCommissionData($dataExportConfigurationTransfer);
            $dataExportWriteResponseTransfer = $this->dataExportService->write($dataExportBatchTransfer, $dataExportConfigurationTransfer);

            if (!$dataExportWriteResponseTransfer->getIsSuccessful()) {
                $dataExportResultTransfer
                    ->fromArray($dataExportWriteResponseTransfer->toArray(), true)
                    ->setExportCount($offset);

                return $this->createDataExportReportTransfer($dataExportResultTransfer);
            }

            $totalExportedCount += count($dataExportBatchTransfer->getData());
            $offset += $limit;
        } while ($totalExportedCount === $offset);

        $dataExportResultTransfer
            ->setIsSuccessful(true)
            ->setExportCount($totalExportedCount)
            ->setFileName($dataExportWriteResponseTransfer->getFileName());

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
}
