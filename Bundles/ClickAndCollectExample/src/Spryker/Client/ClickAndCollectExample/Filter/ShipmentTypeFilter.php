<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\ClickAndCollectExample\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Spryker\Client\ClickAndCollectExample\Dependency\Client\ClickAndCollectExampleToProductOfferStorageClientInterface;

class ShipmentTypeFilter implements ShipmentTypeFilterInterface
{
    /**
     * @var \Spryker\Client\ClickAndCollectExample\Dependency\Client\ClickAndCollectExampleToProductOfferStorageClientInterface
     */
    protected ClickAndCollectExampleToProductOfferStorageClientInterface $productOfferStorageClient;

    /**
     * @param \Spryker\Client\ClickAndCollectExample\Dependency\Client\ClickAndCollectExampleToProductOfferStorageClientInterface $productOfferStorageClient
     */
    public function __construct(ClickAndCollectExampleToProductOfferStorageClientInterface $productOfferStorageClient)
    {
        $this->productOfferStorageClient = $productOfferStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function filterUnavailableProductOfferShipmentTypes(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer,
        QuoteTransfer $quoteTransfer
    ): ShipmentTypeStorageCollectionTransfer {
        $productOfferStorageTransfers = $this->getProductOfferStoragesGroupedBySku($quoteTransfer);
        $shipmentTypeStorageTransfers = $this->getShipmentTypeStoragesIndexedByKey($shipmentTypeStorageCollectionTransfer);

        $filteredShipmentTypeStorages = $this->filterOutShipmentTypes(
            $shipmentTypeStorageTransfers,
            $productOfferStorageTransfers,
        );

        return (new ShipmentTypeStorageCollectionTransfer())
            ->setShipmentTypeStorages(new ArrayObject($filteredShipmentTypeStorages));
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferStorageTransfer>> $productOfferStorageTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    protected function filterOutShipmentTypes(
        array $shipmentTypeStorageTransfers,
        array $productOfferStorageTransfers
    ): array {
        $filteredShipmentTypeStorages = [];

        foreach ($productOfferStorageTransfers as $concreteProductOfferStorages) {
            if (count($filteredShipmentTypeStorages) === count($shipmentTypeStorageTransfers)) {
                break;
            }

            $filteredShipmentTypeStorages = $this->filterShipmentTypeStorages(
                $concreteProductOfferStorages,
                $shipmentTypeStorageTransfers,
                $filteredShipmentTypeStorages,
            );
        }

        return $filteredShipmentTypeStorages;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    protected function getShipmentTypeStoragesIndexedByKey(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
    ): array {
        $indexedShipmentTypeStorages = [];
        foreach ($shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages() as $shipmentTypeStorageTransfer) {
            $indexedShipmentTypeStorages[$shipmentTypeStorageTransfer->getKeyOrFail()] = $shipmentTypeStorageTransfer;
        }

        return $indexedShipmentTypeStorages;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferStorageTransfer>>
     */
    protected function getProductOfferStoragesGroupedBySku(QuoteTransfer $quoteTransfer): array
    {
        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())
            ->setProductConcreteSkus($this->extractProductOfferSkusFromQuote($quoteTransfer));

        $productOfferStorageTransfers = $this->productOfferStorageClient
            ->getProductOfferStoragesBySkus($productOfferStorageCriteriaTransfer)
            ->getProductOffers();

        $indexedProductOfferStorages = [];
        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            $indexedProductOfferStorages[$productOfferStorageTransfer->getProductConcreteSkuOrFail()][] = $productOfferStorageTransfer;
        }

        return $indexedProductOfferStorages;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return list<string>
     */
    protected function extractProductOfferSkusFromQuote(QuoteTransfer $quoteTransfer): array
    {
        $skus = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getProductOfferReference()) {
                $skus[] = $itemTransfer->getSkuOrFail();
            }
        }

        return array_unique($skus);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     * @param array<string, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     * @param array<string, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $filteredShipmentTypeStorages
     *
     * @return array<string, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    protected function filterShipmentTypeStorages(
        array $productOfferStorageTransfers,
        array $shipmentTypeStorageTransfers,
        array $filteredShipmentTypeStorages
    ): array {
        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            /** @var \ArrayObject<int, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorages */
            $shipmentTypeStorages = $productOfferStorageTransfer->getShipmentTypes();

            if (!$shipmentTypeStorages->count()) {
                continue;
            }

            foreach ($shipmentTypeStorages as $shipmentTypeTransfer) {
                $key = $shipmentTypeTransfer->getKeyOrFail();

                if (array_key_exists($key, $shipmentTypeStorageTransfers)) {
                    $filteredShipmentTypeStorages[$key] = $shipmentTypeStorageTransfers[$key];
                }
            }
        }

        return $filteredShipmentTypeStorages;
    }
}
