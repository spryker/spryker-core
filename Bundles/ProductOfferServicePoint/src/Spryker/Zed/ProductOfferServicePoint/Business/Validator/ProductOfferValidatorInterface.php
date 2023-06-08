<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Validator;

use Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer;

interface ProductOfferValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer $productOfferServiceCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer
     */
    public function validate(
        ProductOfferServiceCollectionResponseTransfer $productOfferServiceCollectionResponseTransfer
    ): ProductOfferServiceCollectionResponseTransfer;
}
