<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface ItemResponseBuilderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $cartResource
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCartItemResource(
        RestResourceInterface $cartResource,
        ItemTransfer $itemTransfer,
        string $localeName
    ): RestResourceInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $guestCartResource
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createGuestCartItemResource(
        RestResourceInterface $guestCartResource,
        ItemTransfer $itemTransfer,
        string $localeName
    ): RestResourceInterface;
}
