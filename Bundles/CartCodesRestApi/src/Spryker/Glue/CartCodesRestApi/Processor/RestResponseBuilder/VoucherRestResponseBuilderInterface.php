<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder;

use ArrayObject;

interface VoucherRestResponseBuilderInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DiscountTransfer> $discountTransfers
     * @param string $parentResourceType
     * @param string $parentResourceId
     *
     * @return array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function createVoucherRestResource(ArrayObject $discountTransfers, string $parentResourceType, string $parentResourceId): array;
}
