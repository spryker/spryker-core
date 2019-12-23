<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business;

use Spryker\Zed\MerchantProductOfferSearch\Business\ProductAbstract\ProductAbstractReader;
use Spryker\Zed\MerchantProductOfferSearch\Business\ProductAbstract\ProductAbstractReaderInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchConfig getConfig()
 */
class MerchantProductOfferSearchBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOfferSearch\Business\ProductAbstract\ProductAbstractReaderInterface
     */
    public function createProductAbstractReader(): ProductAbstractReaderInterface
    {
        return new ProductAbstractReader(
            $this->getRepository()
        );
    }
}
