<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Expander;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer;

class ProductOfferShipmentTypeCollectionRequestExpander implements ProductOfferShipmentTypeCollectionRequestExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferProductOfferShipmentTypeCollectionRequestExpanderInterface
     */
    protected ProductOfferProductOfferShipmentTypeCollectionRequestExpanderInterface $productOfferProductOfferShipmentTypeCollectionRequestExpander;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ShipmentTypeProductOfferShipmentTypeCollectionRequestExpanderInterface
     */
    protected ShipmentTypeProductOfferShipmentTypeCollectionRequestExpanderInterface $shipmentTypeProductOfferShipmentTypeCollectionRequestExpander;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferProductOfferShipmentTypeCollectionRequestExpanderInterface $productOfferProductOfferShipmentTypeCollectionRequestExpander
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ShipmentTypeProductOfferShipmentTypeCollectionRequestExpanderInterface $shipmentTypeProductOfferShipmentTypeCollectionRequestExpander
     */
    public function __construct(
        ProductOfferProductOfferShipmentTypeCollectionRequestExpanderInterface $productOfferProductOfferShipmentTypeCollectionRequestExpander,
        ShipmentTypeProductOfferShipmentTypeCollectionRequestExpanderInterface $shipmentTypeProductOfferShipmentTypeCollectionRequestExpander
    ) {
        $this->productOfferProductOfferShipmentTypeCollectionRequestExpander = $productOfferProductOfferShipmentTypeCollectionRequestExpander;
        $this->shipmentTypeProductOfferShipmentTypeCollectionRequestExpander = $shipmentTypeProductOfferShipmentTypeCollectionRequestExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer
     */
    public function expandProductOfferShipmentTypeCollectionRequestTransfer(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): ProductOfferShipmentTypeCollectionRequestTransfer {
        $productOfferShipmentTypeCollectionRequestTransfer = $this->productOfferProductOfferShipmentTypeCollectionRequestExpander
            ->expandWithProductOffersIds($productOfferShipmentTypeCollectionRequestTransfer);

        return $this->shipmentTypeProductOfferShipmentTypeCollectionRequestExpander
            ->expandWithShipmentTypeIds($productOfferShipmentTypeCollectionRequestTransfer);
    }
}
