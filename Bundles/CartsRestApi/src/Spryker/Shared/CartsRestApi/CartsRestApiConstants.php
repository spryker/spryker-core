<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CartsRestApi;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface CartsRestApiConstants
{
    /**
     * Specification:
     * - Enables reloading of cart items.
     * - Enabling leads to performance decreasing.
     * - Used in {@link \Spryker\Glue\CartsRestApi\Controller\CartsResourceController::getAction()},
     *   {@link \Spryker\Glue\CartsRestApi\Controller\GuestCartsResourceController::getAction()}
     *   {@link \Spryker\Glue\CartsRestApi\Controller\CartItemsResourceController::postAction()},
     *   {@link \Spryker\Glue\CartsRestApi\Controller\CartItemsResourceController::patchAction()},
     *   {@link \Spryker\Glue\CartsRestApi\Controller\CartItemsResourceController::deleteAction()},
     *   {@link \Spryker\Glue\CartsRestApi\Controller\GuestCartItemsResourceController::postAction()},
     *   {@link \Spryker\Glue\CartsRestApi\Controller\GuestCartItemsResourceController::patchAction()},
     *   {@link \Spryker\Glue\CartsRestApi\Controller\GuestCartItemsResourceController::deleteAction()}.
     *
     * @api
     * @var string
     */
    public const IS_QUOTE_RELOAD_ENABLED = 'CARTS_REST_API:IS_QUOTE_RELOAD_ENABLED';
}
