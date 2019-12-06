<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Persistence;

use Generated\Shared\Transfer\ProductOfferValidityCollectionTransfer;

interface ProductOfferValidityRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\ProductOfferValidityCollectionTransfer
     */
    public function getProductOfferValiditiesBecomingActive(): ProductOfferValidityCollectionTransfer;

    /**
     * @return \Generated\Shared\Transfer\ProductOfferValidityCollectionTransfer
     */
    public function getProductOfferValiditiesBecomingInActive(): ProductOfferValidityCollectionTransfer;
}
