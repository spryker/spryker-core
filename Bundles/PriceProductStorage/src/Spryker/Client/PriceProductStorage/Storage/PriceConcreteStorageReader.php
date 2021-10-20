<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Storage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Spryker\Client\Kernel\Locator;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageInterface;
use Spryker\Client\PriceProductStorage\PriceProductStorageConfig;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;

class PriceConcreteStorageReader implements PriceConcreteStorageReaderInterface
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
     * @var array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface>
     */
    protected $priceDimensionPlugins;

    /**
     * @var array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePricesExtractorPluginInterface>
     */
    protected $priceProductExtractorPlugins;

    /**
     * @param \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStorageInterface $storageClient
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceProductStorageKeyGeneratorInterface $priceStorageKeyGenerator
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceProductMapperInterface $priceProductMapper
     * @param array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePriceDimensionPluginInterface> $priceDimensionPlugins
     * @param array<\Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductStoragePricesExtractorPluginInterface> $priceProductExtractorPlugins
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
     * @param int $idProductConcrete
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findPriceProductConcreteTransfers($idProductConcrete): array
    {
        $priceProductTransfers = [];

        foreach ($this->priceDimensionPlugins as $priceDimensionPlugin) {
            $priceProductTransfers = array_merge($priceProductTransfers, $priceDimensionPlugin->findProductConcretePrices($idProductConcrete));
        }

        $priceProductTransfers = array_merge($priceProductTransfers, $this->findDefaultPriceDimensionPriceProductTransfers($idProductConcrete));

        return $priceProductTransfers;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function findDefaultPriceDimensionPriceProductTransfers(int $idProductConcrete): array
    {
        $priceData = $this->findProductConcretePriceData($idProductConcrete);

        if (!$priceData) {
            return [];
        }

        $priceProductStorageTransfer = new PriceProductStorageTransfer();
        $priceProductStorageTransfer->fromArray($priceData, true);

        $priceProductTransfers = $this->priceProductMapper->mapPriceProductStorageTransferToPriceProductTransfers($priceProductStorageTransfer);
        $priceProductTransfers = $this->applyPriceProductExtractorPlugins($idProductConcrete, $priceProductTransfers);

        return $priceProductTransfers;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return array|null
     */
    protected function findProductConcretePriceData(int $idProductConcrete): ?array
    {
        if (PriceProductStorageConfig::isCollectorCompatibilityMode()) {
            $clientLocatorClassName = Locator::class;
            /** @var \Spryker\Client\Product\ProductClientInterface $productClient */
            $productClient = $clientLocatorClassName::getInstance()->product()->client();
            $collectorData = $productClient->getProductConcreteByIdForCurrentLocale($idProductConcrete);
            $priceData = [
                'prices' => $collectorData['prices'],
            ];

            return $priceData;
        }

        $key = $this->priceStorageKeyGenerator->generateKey(PriceProductStorageConstants::PRICE_CONCRETE_RESOURCE_NAME, $idProductConcrete);
        $priceData = $this->storageClient->get($key);

        return $priceData;
    }

    /**
     * @param int $idProductConcrete
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function applyPriceProductExtractorPlugins(int $idProductConcrete, array $priceProductTransfers): array
    {
        foreach ($this->priceProductExtractorPlugins as $extractorPlugin) {
            $priceProductTransfers = array_merge(
                $priceProductTransfers,
                $extractorPlugin->extractProductPricesForProductConcrete($idProductConcrete, $priceProductTransfers),
            );
        }

        return $priceProductTransfers;
    }
}
