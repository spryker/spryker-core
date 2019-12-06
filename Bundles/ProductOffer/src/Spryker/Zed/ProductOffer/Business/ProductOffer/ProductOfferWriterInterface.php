<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\ProductOffer;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferWriterInterface
{
    /**
     * @param int $idProductOffer
     *
     * @throws \Spryker\Zed\ProductOffer\Business\Exception\ProductOfferNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function activateProductOfferById(int $idProductOffer): ProductOfferTransfer;

    /**
     * @param int $idProductOffer
     *
     * @throws \Spryker\Zed\ProductOffer\Business\Exception\ProductOfferNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function deactivateProductOfferById(int $idProductOffer): ProductOfferTransfer;
}
