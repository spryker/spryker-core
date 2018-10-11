<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\CartItem;

use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartItemDeleter extends CartItemDeleter implements GuestCartItemDeleterInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function findCartIdentifier(RestRequestInterface $restRequest): ?string
    {
        $cartsResource = $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        if ($cartsResource !== null) {
            return $cartsResource->getId();
        }

        return null;
    }
}
