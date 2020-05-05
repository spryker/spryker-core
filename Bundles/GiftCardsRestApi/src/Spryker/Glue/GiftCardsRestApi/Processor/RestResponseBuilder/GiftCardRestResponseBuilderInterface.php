<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder;

use ArrayObject;

interface GiftCardRestResponseBuilderInterface
{
    /**
     * @param string $resourceType
     * @param \ArrayObject|\Generated\Shared\Transfer\GiftCardTransfer[] $giftCardTransfers
     * @param string $quoteUuid
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createGiftCardRestResource(string $resourceType, ArrayObject $giftCardTransfers, string $quoteUuid): array;
}
