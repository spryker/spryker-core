<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferRepositoryInterface getRepository()
 */
class MerchantProductOfferFacade extends AbstractFacade implements MerchantProductOfferFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findMerchantByOfferReference(string $productOfferReference): ?ProductOfferTransfer
    {
        return $this->getRepository()->findMerchantProductOfferByOfferReference($productOfferReference);
    }
}
