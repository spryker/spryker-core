<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;

interface ProductOptionValuePriceValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateProductOptionValuePrices(CartChangeTransfer $cartChangeTransfer);
}
