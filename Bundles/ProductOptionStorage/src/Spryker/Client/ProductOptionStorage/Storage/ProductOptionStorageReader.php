<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOptionStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Kernel\Locator;
use Spryker\Client\Kernel\PermissionAwareTrait;
use Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStorageInterface;
use Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToUtilEncodingServiceInterface;
use Spryker\Client\ProductOptionStorage\Mapper\ProductOptionMapperInterface;
use Spryker\Client\ProductOptionStorage\Price\ValuePriceReaderInterface;
use Spryker\Client\ProductOptionStorage\ProductOptionStorageConfig;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductOptionStorage\ProductOptionStorageConfig as SharedProductOptionStorageConfig;

class ProductOptionStorageReader implements ProductOptionStorageReaderInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductOptionStorage\Price\ValuePriceReaderInterface
     */
    protected $valuePriceReader;

    /**
     * @var \Spryker\Client\ProductOptionStorage\Mapper\ProductOptionMapperInterface
     */
    protected $productOptionMapper;

    /**
     * @var \Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\ProductOptionStorage\Dependency\Client\ProductOptionStorageToStorageInterface $storageClient
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductOptionStorage\Price\ValuePriceReaderInterface $valuePriceReader
     * @param \Spryker\Client\ProductOptionStorage\Mapper\ProductOptionMapperInterface $productOptionMapper
     * @param \Spryker\Client\ProductOptionStorage\Dependency\Service\ProductOptionStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductOptionStorageToStorageInterface $storageClient,
        Store $store,
        ProductOptionStorageToSynchronizationServiceInterface $synchronizationService,
        ValuePriceReaderInterface $valuePriceReader,
        ProductOptionMapperInterface $productOptionMapper,
        ProductOptionStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->storageClient = $storageClient;
        $this->store = $store;
        $this->synchronizationService = $synchronizationService;
        $this->valuePriceReader = $valuePriceReader;
        $this->productOptionMapper = $productOptionMapper;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer|null
     */
    public function getProductOptions(int $idProductAbstract, string $localeName): ?ProductAbstractOptionStorageTransfer
    {
        return $this->findProductOptionsByByIdProductAbstract($idProductAbstract, $localeName);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer|null
     */
    public function getProductOptionsForCurrentStore(int $idProductAbstract): ?ProductAbstractOptionStorageTransfer
    {
        return $this->findProductOptionsByByIdProductAbstract($idProductAbstract);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[]
     */
    public function getBulkProductOptions(array $productAbstractIds): array
    {
        $productOptionStorageDataItems = $this->getProductOptionStorageDataItems(
            $this->generateStorageKeys($productAbstractIds)
        );
        $productAbstractOptionStorageTransfers =
            $this->productOptionMapper->mapProductAbstractOptionStorageDataItemsToProductAbstractOptionStorageTransfers(
                $productOptionStorageDataItems
            );
        $productAbstractOptionStorageTransfers = $this->indexProductAbstractOptionStorageTransfersByIdProductAbstract(
            $productAbstractOptionStorageTransfers
        );

        if (!$this->can('SeePricePermissionPlugin')) {
            return $productAbstractOptionStorageTransfers;
        }

        return $this->valuePriceReader->resolveProductAbstractOptionStorageTransfersProductOptionValuePrices(
            $productAbstractOptionStorageTransfers
        );
    }

    /**
     * @param int $idProductAbstract
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer|null
     */
    protected function findProductOptionsByByIdProductAbstract(
        int $idProductAbstract,
        ?string $localeName = null
    ): ?ProductAbstractOptionStorageTransfer {
        $productAbstractOptionStorageData = $this->findStorageData($idProductAbstract, $localeName);
        if (!$productAbstractOptionStorageData) {
            return null;
        }

        $productAbstractOptionStorageTransfer =
            $this->productOptionMapper->mapProductAbstractOptionStorageDataItemToProductAbstractOptionStorageTransfer(
                $productAbstractOptionStorageData,
                new ProductAbstractOptionStorageTransfer()
            );

        if (!$this->can('SeePricePermissionPlugin')) {
            return $productAbstractOptionStorageTransfer;
        }

        return $this->valuePriceReader->resolveProductAbstractOptionStorageTransferProductOptionValuePrices(
            $productAbstractOptionStorageTransfer
        );
    }

    /**
     * @param int $idProductAbstract
     * @param string|null $localeName
     *
     * @return array|null
     */
    protected function findStorageData(int $idProductAbstract, ?string $localeName = null): ?array
    {
        if (ProductOptionStorageConfig::isCollectorCompatibilityMode()) {
            if ($localeName === null) {
                $localeName = Store::getInstance()->getCurrentLocale();
            }
            $clientLocatorName = Locator::class;
            /** @var \Spryker\Client\ProductOption\ProductOptionClientInterface $productOptionClient */
            $productOptionClient = $clientLocatorName::getInstance()->productOption()->client();

            $collectorData = $productOptionClient->getProductOptions($idProductAbstract, $localeName);

            $formattedCollectorData = [
                'id_product_abstract' => $idProductAbstract,
                'product_option_groups' => [],
            ];

            foreach ($collectorData->getProductOptionGroups() as $productOptionGroupTransfer) {
                $productOptionData = $productOptionGroupTransfer->toArray();
                $productOptionData['product_option_values'] = $productOptionData['values'];
                unset($productOptionData['values']);

                $formattedCollectorData['product_option_groups'][] = $productOptionData;
            }

            return $formattedCollectorData;
        }

        $key = $this->generateStorageKey($idProductAbstract);

        return $this->storageClient->get($key) ?? null;
    }

    /**
     * @param string[] $productOptionStorageKeys
     *
     * @return array
     */
    protected function getProductOptionStorageDataItems(array $productOptionStorageKeys): array
    {
        $productOptionStorageDataItems = [];
        $productOptionStorageEncodedData = $this->storageClient->getMulti($productOptionStorageKeys);
        foreach ($productOptionStorageEncodedData as $productOptionStorageKey => $productOptionStorageEncodedDataItem) {
            if (!$productOptionStorageEncodedDataItem) {
                continue;
            }

            $productOptionStorageDataItems[] =
                $this->utilEncodingService->decodeJson($productOptionStorageEncodedDataItem, true);
        }

        return $productOptionStorageDataItems;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function generateStorageKey(int $idProductAbstract): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setStore($this->store->getStoreName())
            ->setReference((string)$idProductAbstract);

        return $this->synchronizationService
            ->getStorageKeyBuilder(SharedProductOptionStorageConfig::PRODUCT_ABSTRACT_OPTION_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return string[]
     */
    protected function generateStorageKeys(array $productAbstractIds): array
    {
        $productOptionStorageKeys = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $productOptionStorageKeys[$idProductAbstract] = $this->generateStorageKey($idProductAbstract);
        }

        return $productOptionStorageKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[] $productAbstractOptionStorageTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[]
     */
    protected function indexProductAbstractOptionStorageTransfersByIdProductAbstract(
        array $productAbstractOptionStorageTransfers
    ): array {
        $indexedProductAbstractOptionStorageTransfers = [];
        foreach ($productAbstractOptionStorageTransfers as $productAbstractOptionStorageTransfer) {
            $idProductAbstract = $productAbstractOptionStorageTransfer->getIdProductAbstract();
            $indexedProductAbstractOptionStorageTransfers[$idProductAbstract] = $productAbstractOptionStorageTransfer;
        }

        return $indexedProductAbstractOptionStorageTransfers;
    }
}
