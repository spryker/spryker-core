<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Reader;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer;
use Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface;
use Spryker\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeConfig;

class ProductOfferShipmentTypeReader implements ProductOfferShipmentTypeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeConfig
     */
    protected ProductOfferShipmentTypeConfig $productOfferShipmentTypeConfig;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface
     */
    protected ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferReaderInterface
     */
    protected ProductOfferReaderInterface $productOfferReader;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface
     */
    protected ShipmentTypeReaderInterface $shipmentTypeReader;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeConfig $productOfferShipmentTypeConfig
     * @param \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferReaderInterface $productOfferReader
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface $shipmentTypeReader
     */
    public function __construct(
        ProductOfferShipmentTypeConfig $productOfferShipmentTypeConfig,
        ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository,
        ProductOfferReaderInterface $productOfferReader,
        ShipmentTypeReaderInterface $shipmentTypeReader
    ) {
        $this->productOfferShipmentTypeConfig = $productOfferShipmentTypeConfig;
        $this->productOfferShipmentTypeRepository = $productOfferShipmentTypeRepository;
        $this->productOfferReader = $productOfferReader;
        $this->shipmentTypeReader = $shipmentTypeReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer $productOfferShipmentTypeIteratorCriteriaTransfer
     *
     * @return iterable<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer>>
     */
    public function getProductOfferShipmentTypesIterator(
        ProductOfferShipmentTypeIteratorCriteriaTransfer $productOfferShipmentTypeIteratorCriteriaTransfer
    ): iterable {
        $productOfferIds = $productOfferShipmentTypeIteratorCriteriaTransfer
            ->getProductOfferShipmentTypeIteratorConditionsOrFail()
            ->getProductOfferIds();

        $productOfferIdsChunks = array_chunk(
            $productOfferIds,
            $this->productOfferShipmentTypeConfig->getProductOfferShipmentTypeReadBatchSize(),
        );

        foreach ($productOfferIdsChunks as $productOfferIdsChunk) {
            $productOfferShipmentTypeCollectionTransfer = $this->getProductOfferShipmentTypeCollectionTransfer($productOfferIdsChunk);

            $productOfferShipmentTypeCollectionTransfer = $this->productOfferReader->getProductOffersForProductOfferShipmentTypeCollection(
                $productOfferShipmentTypeCollectionTransfer,
                $productOfferShipmentTypeIteratorCriteriaTransfer,
            );
            $productOfferShipmentTypeCollectionTransfer = $this->shipmentTypeReader->getShipmentTypesForProductOfferShipmentTypeCollection(
                $productOfferShipmentTypeCollectionTransfer,
                $productOfferShipmentTypeIteratorCriteriaTransfer,
            );

            yield $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes();
        }
    }

    /**
     * @param list<int> $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    protected function getProductOfferShipmentTypeCollectionTransfer(array $productOfferIds): ProductOfferShipmentTypeCollectionTransfer
    {
        $productOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeConditionsTransfer())
            ->setGroupByIdProductOffer(true)
            ->setProductOfferIds($productOfferIds);

        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())
            ->setProductOfferShipmentTypeConditions($productOfferShipmentTypeConditionsTransfer);

        return $this->productOfferShipmentTypeRepository->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);
    }
}
