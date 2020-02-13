<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferReader\MerchantProductOfferReader;
use Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferReader\MerchantProductOfferReaderInterface;
use Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface;
use Spryker\Zed\MerchantProductOffer\MerchantProductOfferDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductOffer\MerchantProductOfferConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferRepositoryInterface getRepository()
 */
class MerchantProductOfferBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferReader\MerchantProductOfferReaderInterface
     */
    public function createMerchantProductOfferReader(): MerchantProductOfferReaderInterface
    {
        return new MerchantProductOfferReader(
            $this->getProductOfferFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): MerchantProductOfferToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
