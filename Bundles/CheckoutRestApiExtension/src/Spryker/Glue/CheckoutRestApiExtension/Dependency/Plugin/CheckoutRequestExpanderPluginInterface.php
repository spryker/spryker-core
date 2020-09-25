<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

/**
 * Plugin interface is used to expand the `RestCheckoutRequestAttributesTransfer` with additional information.
 *
 * Executes before sending the checkout-data or checkout Zed requests.
 */
interface CheckoutRequestExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `RestCheckoutRequestAttributesTransfer` with additional data.
     * - Uses `RestRequest`.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function expand(
        RestRequestInterface $restRequest,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutRequestAttributesTransfer;
}
