<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Reader;

use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface;
use Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface;

class ProductImageBulkReader implements ProductImageBulkReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface
     */
    protected $productImageRepository;

    /**
     * @var \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface $productImageRepository
     * @param \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface $localeFacade
     */
    public function __construct(ProductImageRepositoryInterface $productImageRepository, ProductImageToLocaleInterface $localeFacade)
    {
        $this->productImageRepository = $productImageRepository;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int[] $productIds
     * @param string $productImageSetName
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[][]
     */
    public function getProductImagesByProductIdsAndProductImageSetName(array $productIds, string $productImageSetName): array
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $productImageSetTransfers = $this
            ->productImageRepository
            ->getProductImagesSetTransfersByProductIdsAndIdLocale($productIds, $localeTransfer->getIdLocale());

        if (count($productImageSetTransfers) === 0) {
            return [];
        }

        $productSetIds = $this->getImageSetIdsByName($productImageSetTransfers, $productImageSetName);

        return $this->getProductImagesByProductSetIds($productSetIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     * @param string $productImageSetName
     *
     * @return int[]
     */
    protected function getImageSetIdsByName(array $productImageSetTransfers, string $productImageSetName): array
    {
        $productSetIds = [];
        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            if ($productImageSetTransfer->getName() === $productImageSetName) {
                $productSetIds[$productImageSetTransfer->getIdProduct()] = $productImageSetTransfer->getIdProductImageSet();
                continue;
            }
            if (!isset($productSetIds[$productImageSetTransfer->getIdProduct()])) {
                $productSetIds[$productImageSetTransfer->getIdProduct()] = $productImageSetTransfer->getIdProductImageSet();
            }
        }

        return $productSetIds;
    }

    /**
     * @param int[] $productSetIds
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[][]
     */
    protected function getProductImagesByProductSetIds(array $productSetIds): array
    {
        $productImageTransfersByProductId = [];
        $productImageCollection = $this->productImageRepository->getProductImagesByProductSetIds($productSetIds);
        $productIdsByProductImageSetIds = array_flip($productSetIds);
        foreach ($productImageCollection as $productSetId => $productImageTransfers) {
            $productId = $productIdsByProductImageSetIds[$productSetId];
            $productImageTransfersByProductId[$productId] = $productImageTransfers;
        }

        return $productImageTransfersByProductId;
    }
}
