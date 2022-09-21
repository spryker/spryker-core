<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductBundleStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductBundleStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToProductStorageClientInterface;
use Spryker\Client\ProductBundleStorage\Mapper\ProductBundleStorageMapperInterface;
use Spryker\Client\ProductBundleStorage\Reader\ProductBundleStorageReaderInterface;

class ProductViewProductBundleExpander implements ProductViewProductBundleExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductBundleStorage\Reader\ProductBundleStorageReaderInterface
     */
    protected ProductBundleStorageReaderInterface $productBundleStorageReader;

    /**
     * @var \Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToProductStorageClientInterface
     */
    protected ProductBundleStorageToProductStorageClientInterface $productStorageClient;

    /**
     * @var \Spryker\Client\ProductBundleStorage\Mapper\ProductBundleStorageMapperInterface
     */
    protected ProductBundleStorageMapperInterface $productBundleStorageMapper;

    /**
     * @param \Spryker\Client\ProductBundleStorage\Reader\ProductBundleStorageReaderInterface $productBundleStorageReader
     * @param \Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\ProductBundleStorage\Mapper\ProductBundleStorageMapperInterface $productBundleStorageMapper
     */
    public function __construct(
        ProductBundleStorageReaderInterface $productBundleStorageReader,
        ProductBundleStorageToProductStorageClientInterface $productStorageClient,
        ProductBundleStorageMapperInterface $productBundleStorageMapper
    ) {
        $this->productBundleStorageReader = $productBundleStorageReader;
        $this->productStorageClient = $productStorageClient;
        $this->productBundleStorageMapper = $productBundleStorageMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array<string, mixed> $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, string $localeName): ProductViewTransfer
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return $productViewTransfer;
        }

        $productBundleStorageTransfer = $this->getProductBundleStorageTransfer($productViewTransfer);

        if (!$productBundleStorageTransfer) {
            return $productViewTransfer;
        }

        $productForProductBundleStorageTransfers = $productBundleStorageTransfer->getBundledProducts();
        $productConcreteIds = $this->extractProductConcreteIdsFromProductForProductBundleStorageTransfers($productForProductBundleStorageTransfers);

        $productViewTransfers = $this->productStorageClient->getProductConcreteViewTransfers(
            $productConcreteIds,
            $localeName,
        );
        $productViewTransfersIndexedByIdProductConcrete = $this->getProductViewTransfersIndexedByIdProductConcrete(
            $productViewTransfers,
        );

        $this->expandBundledProductTransfers($productForProductBundleStorageTransfers, $productViewTransfersIndexedByIdProductConcrete);

        return $productViewTransfer->setBundledProducts($productForProductBundleStorageTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductForProductBundleStorageTransfer> $productForProductBundleStorageTransfers
     * @param array<int, \Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfersIndexedByIdProductConcrete
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductForProductBundleStorageTransfer>
     */
    protected function expandBundledProductTransfers(
        ArrayObject $productForProductBundleStorageTransfers,
        array $productViewTransfersIndexedByIdProductConcrete
    ): ArrayObject {
        foreach ($productForProductBundleStorageTransfers as $productForProductBundleStorageTransfer) {
            $productConcreteViewTransfer = $productViewTransfersIndexedByIdProductConcrete[$productForProductBundleStorageTransfer->getIdProductConcrete()] ?? null;
            if ($productConcreteViewTransfer === null) {
                continue;
            }

            $this->productBundleStorageMapper->mapProductViewTransferToProductForProductBundleStorageTransfer(
                $productConcreteViewTransfer,
                $productForProductBundleStorageTransfer,
            );
        }

        return $productForProductBundleStorageTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductForProductBundleStorageTransfer> $productForProductBundleStorageTransfers
     *
     * @return array
     */
    protected function extractProductConcreteIdsFromProductForProductBundleStorageTransfers(ArrayObject $productForProductBundleStorageTransfers): array
    {
        $productConcreteIds = [];
        foreach ($productForProductBundleStorageTransfers as $productForProductBundleStorageTransfer) {
            $productConcreteIds[] = $productForProductBundleStorageTransfer->getIdProductConcreteOrFail();
        }

        return $productConcreteIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer|null
     */
    protected function getProductBundleStorageTransfer(ProductViewTransfer $productViewTransfer): ?ProductBundleStorageTransfer
    {
        $productConcreteIds = [$productViewTransfer->getIdProductConcreteOrFail()];
        $productBundleStorageCriteriaTransfer = (new ProductBundleStorageCriteriaTransfer())
            ->setProductConcreteIds($productConcreteIds);
        $productBundleStorageTransfers = $this->productBundleStorageReader->getProductBundles($productBundleStorageCriteriaTransfer);

        return array_shift($productBundleStorageTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ProductViewTransfer>
     */
    protected function getProductViewTransfersIndexedByIdProductConcrete(array $productViewTransfers): array
    {
        $productConcreteViewTransfersIndexedByIdProductConcrete = [];

        foreach ($productViewTransfers as $productViewTransfer) {
            $productConcreteViewTransfersIndexedByIdProductConcrete[$productViewTransfer->getIdProductConcreteOrFail()] = $productViewTransfer;
        }

        return $productConcreteViewTransfersIndexedByIdProductConcrete;
    }
}
