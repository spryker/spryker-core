<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferValidityToProductOfferFacadeInterface
{
    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function activateProductOfferById(int $idProductOffer): ?ProductOfferTransfer;

    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function deactivateProductOfferById(int $idProductOffer): ?ProductOfferTransfer;
}
