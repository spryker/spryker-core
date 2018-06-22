<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\DataTransformer;

use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use SplFileObject;
use Spryker\Zed\ProductListGui\Dependency\Service\ProductListGuiToUtilCsvServiceInterface;
use Spryker\Zed\ProductListGui\Persistence\ProductListGuiRepositoryInterface;

class CsvToProductsConcreteTransformer
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
     * @param \SplFileObject $productsConcreteCsv
     * @param \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer $productConcreteRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer
     */
    public function applyCsvFile(
        SplFileObject $productsConcreteCsv,
        ProductListProductConcreteRelationTransfer $productConcreteRelationTransfer
    ): ProductListProductConcreteRelationTransfer {
        $productTable = $this->csvService->readFile($productsConcreteCsv);

        $productSkus = [];
        foreach ($productTable as $productRow) {
            $productSkus[] = $productRow[0];
        }

        $productIds = $this->repository->getProductsIdsFromSkus($productSkus);

        $productIds = array_merge($productConcreteRelationTransfer->getProductIds(), $productIds);
        $productConcreteRelationTransfer->setProductIds($productIds);

        return $productConcreteRelationTransfer;
    }
}
