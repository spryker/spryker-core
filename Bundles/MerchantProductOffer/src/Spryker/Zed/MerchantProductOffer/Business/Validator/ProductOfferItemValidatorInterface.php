<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business\Validator;

interface ProductOfferItemValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function validateItems(array $itemTransfers): array;
}
