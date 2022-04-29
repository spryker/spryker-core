<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferUpdateFormDataProviderInterface
{
    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function getData(int $idProductOffer): ?ProductOfferTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(ProductAbstractTransfer $productAbstractTransfer): array;
}
