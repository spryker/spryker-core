<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Exporter;

use Generated\Shared\Transfer\CsvFileTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductFacadeInterface;
use Spryker\Zed\ProductListGui\Dependency\Service\ProductListGuiToUtilCsvServiceInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductListExporter implements ProductListExporterInterface
{
    protected const FILE_HEADER = 'Sku';
    protected const FORMAT_FILE_NAME = '%s.csv';

    /**
     * @var \Spryker\Zed\ProductListGui\Dependency\Service\ProductListGuiToUtilCsvServiceInterface
     */
    protected $csvService;

    /**
     * @var \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductListGui\Dependency\Service\ProductListGuiToUtilCsvServiceInterface $csvService
     * @param \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductListGuiToUtilCsvServiceInterface $csvService,
        ProductListGuiToProductFacadeInterface $productFacade
    ) {
        $this->csvService = $csvService;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportToCsvFile(ProductListTransfer $productListTransfer): StreamedResponse
    {
        $productIds = $productListTransfer->getProductListProductConcreteRelation()->getProductIds();
        if (!$productIds) {
            $csvFileTransfer = $this->createCsvFileTransfer($productListTransfer->getTitle(), []);

            return $this->csvService->exportFile($csvFileTransfer);
        }

        $productsSku = $this->productFacade->getProductConcreteSkusByConcreteIds($productIds);
        $productsSku = array_keys($productsSku);

        $productsSku = $this->prepareDataForExport($productsSku);
        $csvFileTransfer = $this->createCsvFileTransfer($productListTransfer->getTitle(), $productsSku);

        return $this->csvService->exportFile($csvFileTransfer);
    }

    /**
     * @param string $title
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CsvFileTransfer
     */
    protected function createCsvFileTransfer(string $title, array $data): CsvFileTransfer
    {
        $csvFileTransfer = new CsvFileTransfer();
        $csvFileTransfer->setHeader([static::FILE_HEADER]);
        $csvFileTransfer->setFileName(sprintf(static::FORMAT_FILE_NAME, $title));
        $csvFileTransfer->setData($data);

        return $csvFileTransfer;
    }

    /**
     * @param array $productsSku
     *
     * @return array
     */
    protected function prepareDataForExport(array $productsSku): array
    {
        return array_map(
            function ($item) {
                return [$item];
            },
            $productsSku
        );
    }
}
