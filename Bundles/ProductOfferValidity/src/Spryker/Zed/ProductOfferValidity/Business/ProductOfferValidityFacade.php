<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferValidity\Business\ProductOfferValidityBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferValidity\Persistence\ProductOfferValidityRepositoryInterface getRepository()
 */
class ProductOfferValidityFacade extends AbstractFacade implements ProductOfferValidityFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function updateProductOfferStatusByValidityDate(): void
    {
        $this->getFactory()
            ->createProductOfferSwitcher()
            ->updateProductOfferValidity();
    }
}
