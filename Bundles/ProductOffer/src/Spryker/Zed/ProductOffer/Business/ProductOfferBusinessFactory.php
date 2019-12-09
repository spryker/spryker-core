<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOffer\Business\ProductOffer\ProductOfferWriter;
use Spryker\Zed\ProductOffer\Business\ProductOffer\ProductOfferWriterInterface;

/**
 * @method \Spryker\Zed\ProductOffer\ProductOfferConfig getConfig()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface getRepository()
 */
class ProductOfferBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOffer\Business\ProductOffer\ProductOfferWriterInterface
     */
    public function createProductOfferWriter(): ProductOfferWriterInterface
    {
        return new ProductOfferWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
