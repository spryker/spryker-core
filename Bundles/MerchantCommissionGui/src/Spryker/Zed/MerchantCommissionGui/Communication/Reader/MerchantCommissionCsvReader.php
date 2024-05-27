<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Reader;

use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\MerchantCommissionGui\Communication\Mapper\MerchantCommissionCsvMapperInterface;
use Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilCsvServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MerchantCommissionCsvReader implements MerchantCommissionCsvReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommissionGui\Communication\Mapper\MerchantCommissionCsvMapperInterface
     */
    protected MerchantCommissionCsvMapperInterface $merchantCommissionCsvMapper;

    /**
     * @var \Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilCsvServiceInterface
     */
    protected MerchantCommissionGuiToUtilCsvServiceInterface $utilCsvService;

    /**
     * @param \Spryker\Zed\MerchantCommissionGui\Communication\Mapper\MerchantCommissionCsvMapperInterface $merchantCommissionCsvMapper
     * @param \Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilCsvServiceInterface $utilCsvService
     */
    public function __construct(
        MerchantCommissionCsvMapperInterface $merchantCommissionCsvMapper,
        MerchantCommissionGuiToUtilCsvServiceInterface $utilCsvService
    ) {
        $this->merchantCommissionCsvMapper = $merchantCommissionCsvMapper;
        $this->utilCsvService = $utilCsvService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    public function readMerchantCommissionTransfersFromCsvFile(UploadedFile $uploadedFile): array
    {
        $merchantCommissionImportData = $this->utilCsvService->readUploadedFile($uploadedFile);
        /** @var list<string> $headers */
        $headers = array_shift($merchantCommissionImportData);

        $merchantCommissionTransfers = [];
        foreach ($merchantCommissionImportData as $rowNumber => $merchantCommissionRowData) {
            if ($this->isRowDataEmpty($merchantCommissionRowData)) {
                continue;
            }

            $merchantCommissionTransfers[$rowNumber] = $this->merchantCommissionCsvMapper
                ->mapMerchantCommissionRowDataToMerchantCommissionTransfer(
                    array_combine($headers, $merchantCommissionRowData),
                    new MerchantCommissionTransfer(),
                );
        }

        return $merchantCommissionTransfers;
    }

    /**
     * @param array<mixed> $rowData
     *
     * @return bool
     */
    protected function isRowDataEmpty(array $rowData): bool
    {
        return array_filter($rowData) === [];
    }
}
