<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer;

class CartReorderRequestMapper implements CartReorderRequestMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderRequestTransfer
     */
    public function mapRestCartReorderAttributesToCartReorderRequestTransfer(
        RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer,
        CartReorderRequestTransfer $cartReorderRequestTransfer
    ): CartReorderRequestTransfer {
        return $cartReorderRequestTransfer->setIsAmendment(
            $restCartReorderRequestAttributesTransfer->getIsAmendment(),
        );
    }
}
