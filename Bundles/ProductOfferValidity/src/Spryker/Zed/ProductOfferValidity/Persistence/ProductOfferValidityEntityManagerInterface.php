<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Persistence;

use Generated\Shared\Transfer\ProductOfferValidityTransfer;

interface ProductOfferValidityEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferValidityTransfer $productOfferValidityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer
     */
    public function create(ProductOfferValidityTransfer $productOfferValidityTransfer): ProductOfferValidityTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferValidityTransfer $productOfferValidityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer
     */
    public function update(ProductOfferValidityTransfer $productOfferValidityTransfer): ProductOfferValidityTransfer;
}
