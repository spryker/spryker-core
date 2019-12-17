<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferBusinessFactory getFactory()
 */
class MerchantProductOfferFacade extends AbstractFacade implements MerchantProductOfferFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function validateItems(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createProductOfferItemValidator()
            ->validateItems($itemTransfers);
    }
}
