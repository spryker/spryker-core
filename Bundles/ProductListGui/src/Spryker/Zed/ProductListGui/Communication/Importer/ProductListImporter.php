<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Importer;

use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Spryker\Zed\ProductListGui\Dependency\Service\ProductListGuiToUtilCsvServiceInterface;
use Spryker\Zed\ProductListGui\Persistence\ProductListGuiRepositoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductListImporter implements ProductListImporterInterface
{
    /**
     * @var \Spryker\Zed\ProductListGui\Dependency\Service\ProductListGuiToUtilCsvServiceInterface
     */
    protected $csvService;

    /**
     * @var \Spryker\Zed\ProductListGui\Persistence\ProductListGuiRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\ProductListGui\Dependency\Service\ProductListGuiToUtilCsvServiceInterface $csvService
     * @param \Spryker\Zed\ProductListGui\Persistence\ProductListGuiRepositoryInterface $repository
     */
    public function __construct(
        ProductListGuiToUtilCsvServiceInterface $csvService,
        ProductListGuiRepositoryInterface $repository
    ) {
        $this->csvService = $csvService;
        $this->repository = $repository;
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
        $productTable = $this->csvService->readFile($productsConcreteCsv);

        $productSkus = [];
        foreach ($productTable as $productRow) {
            $productSkus[] = $productRow[0];
        }

        $productIds = $this->repository->findProductIdsByProductConcreteSku($productSkus);

        $productIds = array_merge((array)$productConcreteRelationTransfer->getProductIds(), $productIds);
        $productConcreteRelationTransfer->setProductIds($productIds);

        return $productConcreteRelationTransfer;
    }
}
