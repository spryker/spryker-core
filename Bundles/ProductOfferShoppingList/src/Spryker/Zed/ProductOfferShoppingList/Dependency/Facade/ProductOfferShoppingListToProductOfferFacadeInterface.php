<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShoppingList\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferShoppingListToProductOfferFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteria
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaTransfer $productOfferCriteria): ?ProductOfferTransfer;
}
