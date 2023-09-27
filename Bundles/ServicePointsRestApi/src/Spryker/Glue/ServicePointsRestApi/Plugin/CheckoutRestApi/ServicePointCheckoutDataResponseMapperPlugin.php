<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataResponseMapperPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiFactory getFactory()
 */
class ServicePointCheckoutDataResponseMapperPlugin extends AbstractPlugin implements CheckoutDataResponseMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `RestCheckoutDataTransfer.quote` to be set.
     * - Expects `RestCheckoutDataTransfer.quote.items` to be provided.
     * - Expects `RestCheckoutDataTransfer.quote.items.servicePoint.uuid` to be provided.
     * - Requires `RestCheckoutDataTransfer.quote.items.groupKey` to be set if `RestCheckoutDataTransfer.quote.items.servicePoint.uuid` is provided.
     * - Expands `RestCheckoutDataResponseAttributesTransfer.selectedServicePoints` with service points from `RestCheckoutDataTransfer.quote.items.servicePoint`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        return $this->getFactory()
            ->createCheckoutDataResponseAttributesExpander()
            ->expandCheckoutDataResponseAttributesWithSelectedServicePoints(
                $restCheckoutDataTransfer,
                $restCheckoutResponseAttributesTransfer,
            );
    }
}
