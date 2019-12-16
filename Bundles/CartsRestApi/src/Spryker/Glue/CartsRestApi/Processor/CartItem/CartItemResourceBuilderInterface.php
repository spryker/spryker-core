<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\CartItem;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface CartItemResourceBuilderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $cartResource
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function buildCartItemResource(
        RestResourceInterface $cartResource,
        ItemTransfer $itemTransfer,
        string $localeName
    ): RestResourceInterface;
}
