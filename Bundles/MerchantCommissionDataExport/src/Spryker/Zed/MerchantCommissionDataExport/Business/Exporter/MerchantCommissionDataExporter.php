<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer;
use Spryker\Zed\MerchantCommissionDataExport\Business\Formatter\MerchantCommissionAmountFormatterInterface;
use Spryker\Zed\MerchantCommissionDataExport\Business\Mapper\MerchantCommissionDataExportMapperInterface;
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
     * @var \Spryker\Zed\MerchantCommissionDataExport\Business\Mapper\MerchantCommissionDataExportMapperInterface
     */
    protected MerchantCommissionDataExportMapperInterface $merchantCommissionDataExportMapper;

    /**
     * @var \Spryker\Zed\MerchantCommissionDataExport\Persistence\MerchantCommissionDataExportRepositoryInterface
     */
    protected MerchantCommissionDataExportRepositoryInterface $merchantCommissionDataExportRepository;

    /**
     * @var \Spryker\Zed\MerchantCommissionDataExport\Business\Formatter\MerchantCommissionAmountFormatterInterface
     */
    protected MerchantCommissionAmountFormatterInterface $merchantCommissionAmountFormatter;

    /**
     * @var \Spryker\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportConfig
     */
    protected MerchantCommissionDataExportConfig $merchantCommissionDataExportConfig;

    /**
     * @var \Spryker\Zed\MerchantCommissionDataExport\Dependency\Service\MerchantCommissionDataExportToDataExportServiceInterface
     */
    protected MerchantCommissionDataExportToDataExportServiceInterface $dataExportService;

    /**
     * @param \Spryker\Zed\MerchantCommissionDataExport\Business\Mapper\MerchantCommissionDataExportMapperInterface $merchantCommissionDataExportMapper
     * @param \Spryker\Zed\MerchantCommissionDataExport\Persistence\MerchantCommissionDataExportRepositoryInterface $merchantCommissionDataExportRepository
     * @param \Spryker\Zed\MerchantCommissionDataExport\Business\Formatter\MerchantCommissionAmountFormatterInterface $merchantCommissionAmountFormatter
     * @param \Spryker\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportConfig $merchantCommissionDataExportConfig
     * @param \Spryker\Zed\MerchantCommissionDataExport\Dependency\Service\MerchantCommissionDataExportToDataExportServiceInterface $dataExportService
     */
    public function __construct(
        MerchantCommissionDataExportMapperInterface $merchantCommissionDataExportMapper,
        MerchantCommissionDataExportRepositoryInterface $merchantCommissionDataExportRepository,
        MerchantCommissionAmountFormatterInterface $merchantCommissionAmountFormatter,
        MerchantCommissionDataExportConfig $merchantCommissionDataExportConfig,
        MerchantCommissionDataExportToDataExportServiceInterface $dataExportService
    ) {
        $this->merchantCommissionDataExportMapper = $merchantCommissionDataExportMapper;
        $this->merchantCommissionDataExportRepository = $merchantCommissionDataExportRepository;
        $this->merchantCommissionAmountFormatter = $merchantCommissionAmountFormatter;
        $this->merchantCommissionDataExportConfig = $merchantCommissionDataExportConfig;
        $this->dataExportService = $dataExportService;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer
     */
    public function exportByMerchantCommissionExportRequest(
        MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer
    ): MerchantCommissionExportResponseTransfer {
        $merchantCommissionExportResponseTransfer = new MerchantCommissionExportResponseTransfer();

        $dataExportConfigurationTransfer = $this->merchantCommissionDataExportMapper
            ->mapMerchantCommissionExportRequestTransferToDataExportConfigurationTransfer(
                $merchantCommissionExportRequestTransfer,
                new DataExportConfigurationTransfer(),
            );

        $offset = 0;
        $totalExportedCount = 0;
        $limit = $this->merchantCommissionDataExportConfig->getMerchantCommissionReadBatchSize();
        do {
            $dataExportConfigurationTransfer
                ->addFilterCriterion(static::FILTER_CRITERIA_KEY_OFFSET, $offset)
                ->addFilterCriterion(static::FILTER_CRITERIA_KEY_LIMIT, $limit);
            $dataExportBatchTransfer = $this->merchantCommissionDataExportRepository->getMerchantCommissionData($dataExportConfigurationTransfer);
            $dataExportBatchTransfer->setData(
                $this->merchantCommissionAmountFormatter->formatMerchantCommissionAmount($dataExportBatchTransfer->getData()),
            );

            $dataExportWriteResponseTransfer = $this->dataExportService->write($dataExportBatchTransfer, $dataExportConfigurationTransfer);
            if (!$dataExportWriteResponseTransfer->getIsSuccessful()) {
                return $this->merchantCommissionDataExportMapper->mapDataExportWriteResponseTransferToMerchantCommissionExportResponseTransfer(
                    $dataExportWriteResponseTransfer,
                    $merchantCommissionExportResponseTransfer,
                );
            }

            $totalExportedCount += count($dataExportBatchTransfer->getData());
            $offset += $limit;
        } while ($totalExportedCount === $offset);

        return $merchantCommissionExportResponseTransfer;
    }
}
