<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointsRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ServicePointsRestApi\Business\ServicePointsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\ServicePointsRestApi\ServicePointsRestApiConfig getConfig()
 */
class ServicePointCheckoutDataExpanderPlugin extends AbstractPlugin implements CheckoutDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `RestCheckoutDataTransfer.quote` to be provided.
     * - Does nothing `RestCheckoutDataTransfer.quote.items` are not provided.
     * - Expects `RestCheckoutDataTransfer.quote.items.servicePoint.uuid` to be provided.
     * - Extracts `RestCheckoutDataTransfer.quote.items.servicePoint`.
     * - Expands `RestCheckoutDataTransfer` with extracted service points.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    public function expandCheckoutData(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataTransfer {
        return $this->getFacade()->expandCheckoutDataWithAvailableServicePoints(
            $restCheckoutDataTransfer,
            $restCheckoutRequestAttributesTransfer,
        );
    }
}
