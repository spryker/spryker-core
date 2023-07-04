<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Expander;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ProductOfferIndexerInterface;

class ProductOfferProductOfferShipmentTypeCollectionExpander implements ProductOfferProductOfferShipmentTypeCollectionExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ProductOfferIndexerInterface
     */
    protected ProductOfferIndexerInterface $productOfferIndexer;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ProductOfferIndexerInterface $productOfferIndexer
     */
    public function __construct(ProductOfferIndexerInterface $productOfferIndexer)
    {
        $this->productOfferIndexer = $productOfferIndexer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function expandProductOfferShipmentTypeCollectionWithProductOffers(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer,
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): ProductOfferShipmentTypeCollectionTransfer {
        $indexedProductOfferTransfers = $this->productOfferIndexer->getProductOfferTransfersIndexedByIdProductOffer(
            $productOfferCollectionTransfer->getProductOffers(),
        );

        foreach ($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes() as $productOfferShipmentTypeTransfer) {
            $idProductOffer = $productOfferShipmentTypeTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();
            $productOfferShipmentTypeTransfer->setProductOffer($indexedProductOfferTransfers[$idProductOffer]);
        }

        return $productOfferShipmentTypeCollectionTransfer;
    }
}
