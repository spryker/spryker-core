<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityStorage\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor\ProductOfferStorageExtractorInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Sanitizer\ProductOfferServicePointAvailabilitySanitizerInterface;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class ProductOfferServicePointAvailabilityReader implements ProductOfferServicePointAvailabilityReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientInterface
     */
    protected ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientInterface $productOfferAvailabilityStorageClient;

    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Reader\ProductOfferStorageReaderInterface
     */
    protected ProductOfferStorageReaderInterface $productOfferStorageReader;

    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor\ProductOfferStorageExtractorInterface
     */
    protected ProductOfferStorageExtractorInterface $productOfferStorageExtractor;

    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Sanitizer\ProductOfferServicePointAvailabilitySanitizerInterface
     */
    protected ProductOfferServicePointAvailabilitySanitizerInterface $productOfferServicePointAvailabilitySanitizer;

    /**
     * @var list<\Spryker\Client\ProductOfferServicePointAvailabilityStorageExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityFilterPluginInterface>
     */
    protected array $productOfferServicePointAvailabilityFilterPlugins;

    /**
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientInterface $productOfferAvailabilityStorageClient
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Reader\ProductOfferStorageReaderInterface $productOfferStorageReader
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor\ProductOfferStorageExtractorInterface $productOfferStorageExtractor
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Sanitizer\ProductOfferServicePointAvailabilitySanitizerInterface $productOfferServicePointAvailabilitySanitizer
     * @param list<\Spryker\Client\ProductOfferServicePointAvailabilityStorageExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityFilterPluginInterface> $productOfferServicePointAvailabilityFilterPlugins
     */
    public function __construct(
        ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientInterface $productOfferAvailabilityStorageClient,
        ProductOfferStorageReaderInterface $productOfferStorageReader,
        ProductOfferStorageExtractorInterface $productOfferStorageExtractor,
        ProductOfferServicePointAvailabilitySanitizerInterface $productOfferServicePointAvailabilitySanitizer,
        array $productOfferServicePointAvailabilityFilterPlugins
    ) {
        $this->productOfferAvailabilityStorageClient = $productOfferAvailabilityStorageClient;
        $this->productOfferStorageReader = $productOfferStorageReader;
        $this->productOfferStorageExtractor = $productOfferStorageExtractor;
        $this->productOfferServicePointAvailabilitySanitizer = $productOfferServicePointAvailabilitySanitizer;
        $this->productOfferServicePointAvailabilityFilterPlugins = $productOfferServicePointAvailabilityFilterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer
     */
    public function getProductOfferServicePointAvailabilityCollection(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
    ): ProductOfferServicePointAvailabilityCollectionTransfer {
        $this->assertRequiredFields($productOfferServicePointAvailabilityCriteriaTransfer);
        $productOfferServicePointAvailabilityCollectionTransfer = new ProductOfferServicePointAvailabilityCollectionTransfer();
        $productOfferServicePointAvailabilityConditionsTransfer = $productOfferServicePointAvailabilityCriteriaTransfer->getProductOfferServicePointAvailabilityConditionsOrFail();

        $productOfferStorageTransfers = $this->productOfferStorageReader->getProductOfferStorageTransfersByProductOfferServicePointAvailabilityConditions(
            $productOfferServicePointAvailabilityConditionsTransfer,
        );

        if (!$productOfferStorageTransfers->count()) {
            return $productOfferServicePointAvailabilityCollectionTransfer;
        }

        $productOfferAvailabilityStorageTransfers = $this->productOfferAvailabilityStorageClient->getByProductOfferReferences(
            $this->productOfferStorageExtractor->extractProductOfferReferencesFromProductOfferStorages($productOfferStorageTransfers),
            $productOfferServicePointAvailabilityConditionsTransfer->getStoreNameOrFail(),
        );
        $productOfferStorageTransfersIndexedByProductOfferReference = $this->getProductOfferStorageTransfersIndexedByProductOfferReference($productOfferStorageTransfers);

        foreach ($productOfferAvailabilityStorageTransfers as $productOfferAvailabilityStorageTransfer) {
            $productOfferStorageTransfer = $productOfferStorageTransfersIndexedByProductOfferReference[$productOfferAvailabilityStorageTransfer->getProductOfferReferenceOrFail()];
            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceStorageTransfer> $serviceStorageTransfers */
            $serviceStorageTransfers = $productOfferStorageTransfer->getServices();

            $productOfferServicePointAvailabilityCollectionTransfer = $this->expandProductOfferServicePointAvailabilityCollectionWithServicePointAvailabilityResponseItems(
                $serviceStorageTransfers,
                $productOfferServicePointAvailabilityCollectionTransfer,
                $productOfferAvailabilityStorageTransfer,
                $productOfferStorageTransfer,
            );
        }

        $productOfferServicePointAvailabilityCollectionTransfer = $this->executeProductOfferServicePointAvailabilityFilterPlugins(
            $productOfferServicePointAvailabilityCriteriaTransfer,
            $productOfferServicePointAvailabilityCollectionTransfer,
        );

        return $this->productOfferServicePointAvailabilitySanitizer->sanitizeProductOfferStorage(
            $productOfferServicePointAvailabilityCollectionTransfer,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    protected function getProductOfferStorageTransfersIndexedByProductOfferReference(ArrayObject $productOfferStorageTransfers): array
    {
        $productOfferStorageTransfersIndexedByProductOfferReference = [];

        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            $productOfferStorageTransfersIndexedByProductOfferReference[$productOfferStorageTransfer->getProductOfferReferenceOrFail()] = $productOfferStorageTransfer;
        }

        return $productOfferStorageTransfersIndexedByProductOfferReference;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return void
     */
    protected function assertRequiredFields(ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer): void
    {
        $productOfferServicePointAvailabilityCriteriaTransfer
            ->requireProductOfferServicePointAvailabilityConditions()
            ->getProductOfferServicePointAvailabilityConditionsOrFail()
            ->requireStoreName()
            ->requireServiceTypeUuid()
            ->requireProductOfferServicePointAvailabilityRequestItems();

        $productOfferServicePointAvailabilityConditionsTransfer = $productOfferServicePointAvailabilityCriteriaTransfer->getProductOfferServicePointAvailabilityConditionsOrFail();

        if (!$productOfferServicePointAvailabilityConditionsTransfer->getServicePointUuids()) {
            throw new RequiredTransferPropertyException(sprintf(
                'Missing required property "%s" for transfer %s.',
                ProductOfferServicePointAvailabilityConditionsTransfer::SERVICE_POINT_UUIDS,
                $productOfferServicePointAvailabilityConditionsTransfer::class,
            ));
        }

        foreach ($productOfferServicePointAvailabilityConditionsTransfer->getProductOfferServicePointAvailabilityRequestItems() as $productOfferServicePointAvailabilityRequestItemTransfer) {
            $productOfferServicePointAvailabilityRequestItemTransfer->requireProductConcreteSku();
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceStorageTransfer> $serviceStorageTransfers
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer
     */
    protected function expandProductOfferServicePointAvailabilityCollectionWithServicePointAvailabilityResponseItems(
        ArrayObject $serviceStorageTransfers,
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer,
        ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer,
        ProductOfferStorageTransfer $productOfferStorageTransfer
    ): ProductOfferServicePointAvailabilityCollectionTransfer {
        foreach ($serviceStorageTransfers as $serviceStorageTransfer) {
            $productOfferServicePointAvailabilityResponseItemTransfer = $this->createProductOfferServicePointAvailabilityResponseItem(
                $productOfferAvailabilityStorageTransfer,
                $productOfferStorageTransfer,
                $serviceStorageTransfer->getServicePointOrFail()->getUuidOrFail(),
            );
            $productOfferServicePointAvailabilityCollectionTransfer->addProductOfferServicePointAvailabilityResponseItem(
                $productOfferServicePointAvailabilityResponseItemTransfer,
            );
        }

        return $productOfferServicePointAvailabilityCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     * @param string $servicePointUuid
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer
     */
    protected function createProductOfferServicePointAvailabilityResponseItem(
        ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer,
        ProductOfferStorageTransfer $productOfferStorageTransfer,
        string $servicePointUuid
    ): ProductOfferServicePointAvailabilityResponseItemTransfer {
        return (new ProductOfferServicePointAvailabilityResponseItemTransfer())
            ->fromArray($productOfferStorageTransfer->toArray(), true)
            ->setServicePointUuid($servicePointUuid)
            ->setProductOfferStorage($productOfferStorageTransfer)
            ->setIsNeverOutOfStock($productOfferAvailabilityStorageTransfer->getIsNeverOutOfStockOrFail())
            ->setAvailableQuantity($productOfferAvailabilityStorageTransfer->getAvailabilityOrFail()->toInt());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer
     */
    protected function executeProductOfferServicePointAvailabilityFilterPlugins(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer,
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
    ): ProductOfferServicePointAvailabilityCollectionTransfer {
        foreach ($this->productOfferServicePointAvailabilityFilterPlugins as $productOfferServicePointAvailabilityFilterPlugin) {
            $productOfferServicePointAvailabilityCollectionTransfer = $productOfferServicePointAvailabilityFilterPlugin->filter(
                $productOfferServicePointAvailabilityCriteriaTransfer,
                $productOfferServicePointAvailabilityCollectionTransfer,
            );
        }

        return $productOfferServicePointAvailabilityCollectionTransfer;
    }
}
