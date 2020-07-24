<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidityGui\Communication\Reader;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferValidityGuiReaderInterface
{
    /**
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array
     */
    public function getProductOfferValidityData(ProductOfferTransfer $productOfferTransfer): array;
}
