<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferBusinessFactory getFactory()
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
     * @return int|null
     */
    public function findIdMerchantByProductOfferReference(string $productOfferReference): ?int
    {
        return $this->getRepository()->findIdMerchantByOfferReference($productOfferReference);
    }
}
