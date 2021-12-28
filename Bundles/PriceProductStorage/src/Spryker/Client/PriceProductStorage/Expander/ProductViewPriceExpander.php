<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Expander;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductClientInterface;
use Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToPriceProductServiceInterface;
use Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface;
use Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface;

class ProductViewPriceExpander implements ProductViewPriceExpanderInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface
     */
    protected $priceAbstractStorageReader;

    /**
     * @var \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface
     */
    protected $priceConcreteStorageReader;

    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToPriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @var array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductFilterExpanderPluginInterface>
     */
    protected $priceProductFilterExpanderPlugins;

    /**
     * @var array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductExpanderPluginInterface>
     */
    protected $priceProductExpanderPlugins;

    /**
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface $priceAbstractStorageReader
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface $priceConcreteStorageReader
     * @param \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductClientInterface $priceProductClient
     * @param \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToPriceProductServiceInterface $priceProductService
     * @param array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductFilterExpanderPluginInterface> $priceProductFilterExpanderPlugins
     * @param array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductExpanderPluginInterface> $priceProductExpanderPlugins
     */
    public function __construct(
        PriceAbstractStorageReaderInterface $priceAbstractStorageReader,
        PriceConcreteStorageReaderInterface $priceConcreteStorageReader,
        PriceProductStorageToPriceProductClientInterface $priceProductClient,
        PriceProductStorageToPriceProductServiceInterface $priceProductService,
        array $priceProductFilterExpanderPlugins,
        array $priceProductExpanderPlugins
    ) {
        $this->priceAbstractStorageReader = $priceAbstractStorageReader;
        $this->priceConcreteStorageReader = $priceConcreteStorageReader;
        $this->priceProductClient = $priceProductClient;
        $this->priceProductService = $priceProductService;
        $this->priceProductFilterExpanderPlugins = $priceProductFilterExpanderPlugins;
        $this->priceProductExpanderPlugins = $priceProductExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewPriceData(ProductViewTransfer $productViewTransfer)
    {
        $priceProductAbstractTransfers = $this->findPriceAbstractData($productViewTransfer);
        $priceProductConcreteTransfers = $this->findPriceConcreteData($productViewTransfer);

        if (!$priceProductConcreteTransfers) {
            return $this->setPrices($productViewTransfer, $priceProductAbstractTransfers);
        }

        $priceProductConcreteTransfers = $this->priceProductService->mergeConcreteAndAbstractPrices(
            $priceProductAbstractTransfers,
            $priceProductConcreteTransfers,
        );

        return $this->setPrices($productViewTransfer, $priceProductConcreteTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function setPrices(ProductViewTransfer $productViewTransfer, array $priceProductTransfers): ProductViewTransfer
    {
        $priceProductTransfers = $this->executePriceProductExpanderPlugins($priceProductTransfers, $productViewTransfer);

        $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransferByPriceProductFilter(
            $priceProductTransfers,
            $this->getPriceProductFilterFromProductView($productViewTransfer),
        );

        return $productViewTransfer
            ->setCurrentProductPrice($currentProductPriceTransfer)
            ->setPrice($currentProductPriceTransfer->getPrice())
            ->setPrices($currentProductPriceTransfer->getPrices());
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function executePriceProductExpanderPlugins(
        array $priceProductTransfers,
        ProductViewTransfer $productViewTransfer
    ): array {
        foreach ($this->priceProductExpanderPlugins as $priceProductExpanderPlugin) {
            $priceProductTransfers = $priceProductExpanderPlugin->expand($priceProductTransfers, $productViewTransfer);
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function findPriceAbstractData(ProductViewTransfer $productViewTransfer): array
    {
        if (!$productViewTransfer->getIdProductAbstract()) {
            return [];
        }

        return $this->priceAbstractStorageReader->findPriceProductAbstractTransfers($productViewTransfer->getIdProductAbstract());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function findPriceConcreteData(ProductViewTransfer $productViewTransfer): array
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return [];
        }

        return $this->priceConcreteStorageReader->findPriceProductConcreteTransfers($productViewTransfer->getIdProductConcrete());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function getPriceProductFilterFromProductView(ProductViewTransfer $productViewTransfer): PriceProductFilterTransfer
    {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($productViewTransfer->getQuantity());

        foreach ($this->priceProductFilterExpanderPlugins as $priceProductFilterExpanderPlugin) {
            $priceProductFilterTransfer = $priceProductFilterExpanderPlugin->expand($productViewTransfer, $priceProductFilterTransfer);
        }

        return $priceProductFilterTransfer;
    }
}
