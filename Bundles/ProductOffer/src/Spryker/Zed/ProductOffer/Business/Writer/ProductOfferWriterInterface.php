<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Writer;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function create(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;
}
