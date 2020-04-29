<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface GiftCardsRestResponseBuilderInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\GiftCardTransfer[] $giftCardTransfers
     * @param string $quoteReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createGiftCardsRestResource(ArrayObject $giftCardTransfers, string $quoteReference): RestResourceInterface;
}
