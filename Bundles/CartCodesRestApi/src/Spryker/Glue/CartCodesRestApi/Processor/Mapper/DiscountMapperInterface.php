<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\RestDiscountsAttributesTransfer;

interface DiscountMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\RestDiscountsAttributesTransfer $restDiscountsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestDiscountsAttributesTransfer
     */
    public function mapDiscountDataToRestDiscountsAttributesTransfer(
        DiscountTransfer $discountTransfer,
        RestDiscountsAttributesTransfer $restDiscountsAttributesTransfer
    ): RestDiscountsAttributesTransfer;
}
