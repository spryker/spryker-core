<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Dependency\Facade;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface SalesMerchantConnectorToMerchantProductOfferFacadeInterface
{
    /**
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findMerchantByProductOfferReference(string $productOfferReference): ?ProductOfferTransfer;
}
