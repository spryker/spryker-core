<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Importer;

use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductFacadeInterface;
use Spryker\Zed\ProductListGui\Dependency\Service\ProductListGuiToUtilCsvServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductListImporter implements ProductListImporterInterface
{
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
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $productsConcreteCsv
     * @param \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer $productConcreteRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer
     */
    public function importFromCsvFile(
        UploadedFile $productsConcreteCsv,
        ProductListProductConcreteRelationTransfer $productConcreteRelationTransfer
    ): ProductListProductConcreteRelationTransfer {
        $productTable = $this->csvService->readUploadedFile($productsConcreteCsv);

        $productsSku = [];
        foreach ($productTable as $productRow) {
            $productsSku[] = $productRow[0];
        }

        $productIds = $this->productFacade->getProductConcreteIdsByConcreteSkus($productsSku);
        $productIds = array_values($productIds);

        $productIds = array_merge($productConcreteRelationTransfer->getProductIds() ?: [], $productIds);
        $productConcreteRelationTransfer->setProductIds($productIds);

        return $productConcreteRelationTransfer;
    }
}
