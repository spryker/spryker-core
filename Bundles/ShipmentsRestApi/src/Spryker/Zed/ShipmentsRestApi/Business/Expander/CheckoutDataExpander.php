<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentsRestApi\Business\Expander;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface;

class CheckoutDataExpander implements CheckoutDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(ShipmentsRestApiToShipmentFacadeInterface $shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    public function expandCheckoutDataWithAvailableShipmentMethods(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataTransfer {
        if (!$restCheckoutDataTransfer->getQuote()) {
            return $restCheckoutDataTransfer;
        }

        $shipmentMethodsCollectionTransfer = $this->shipmentFacade
            ->getAvailableMethodsByShipment($restCheckoutDataTransfer->getQuote());
        $restCheckoutDataTransfer->setAvailableShipmentMethods($shipmentMethodsCollectionTransfer);

        return $restCheckoutDataTransfer;
    }
}
