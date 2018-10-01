<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Storage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageInterface;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;

class PriceAbstractStorageReader implements PriceAbstractStorageReaderInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\PriceProductStorage\Storage\PriceProductStorageKeyGeneratorInterface
     */
    protected $priceStorageKeyGenerator;

    /**
     * @var \Spryker\Client\PriceProductStorage\Storage\PriceProductMapperInterface
     */
    protected $priceProductMapper;

    /**
     * @var \Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface[]
     */
    protected $priceDimensionPlugins;

    /**
     * @var \Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePricesExtractorPluginInterface[]
     */
    protected $priceProductExtractorPlugins;

    /**
     * @param \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageInterface $storageClient
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceProductStorageKeyGeneratorInterface $priceStorageKeyGenerator
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceProductMapperInterface $priceProductMapper
     * @param \Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface[] $priceDimensionPlugins
     * @param \Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePricesExtractorPluginInterface[] $priceProductExtractorPlugins
     */
    public function __construct(
        PriceProductStorageToStorageInterface $storageClient,
        PriceProductStorageKeyGeneratorInterface $priceStorageKeyGenerator,
        PriceProductMapperInterface $priceProductMapper,
        array $priceDimensionPlugins,
        array $priceProductExtractorPlugins
    ) {
        $this->storageClient = $storageClient;
        $this->priceStorageKeyGenerator = $priceStorageKeyGenerator;
        $this->priceProductMapper = $priceProductMapper;
        $this->priceDimensionPlugins = $priceDimensionPlugins;
        $this->priceProductExtractorPlugins = $priceProductExtractorPlugins;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPriceProductAbstractTransfers(int $idProductAbstract): array
    {
        $priceProductTransfers = [];

        foreach ($this->priceDimensionPlugins as $priceDimensionPlugin) {
            $priceProductTransfers = array_merge($priceProductTransfers, $priceDimensionPlugin->findProductAbstractPrices($idProductAbstract));
        }

        $priceProductTransfers = array_merge($priceProductTransfers, $this->findDefaultPriceDimensionPriceProductTransfers($idProductAbstract));

        return $priceProductTransfers;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function findDefaultPriceDimensionPriceProductTransfers(int $idProductAbstract): array
    {
        $key = $this->priceStorageKeyGenerator->generateKey(PriceProductStorageConstants::PRICE_ABSTRACT_RESOURCE_NAME, $idProductAbstract);
        $priceData = $this->storageClient->get($key);

        if (!$priceData) {
            return [];
        }

        $priceProductStorageTransfer = new PriceProductStorageTransfer();
        $priceProductStorageTransfer->fromArray($priceData, true);

        $priceProductTransfers = $this->priceProductMapper->mapPriceProductStorageTransferToPriceProductTransfers($priceProductStorageTransfer);
        $priceProductTransfers = $this->applyPriceProductExtractorPlugins($priceProductTransfers);

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function applyPriceProductExtractorPlugins(array $priceProductTransfers): array
    {
        foreach ($this->priceProductExtractorPlugins as $extractorPlugin) {
            $priceProductTransfers = array_merge(
                $priceProductTransfers,
                $extractorPlugin->extractProductPricesForProductAbstract($priceProductTransfers)
            );
        }

        return $priceProductTransfers;
    }
}
