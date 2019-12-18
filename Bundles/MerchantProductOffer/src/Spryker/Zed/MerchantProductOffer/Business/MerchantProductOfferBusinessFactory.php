<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProductOffer\Business\Validator\MerchantProductOfferItemValidator;
use Spryker\Zed\MerchantProductOffer\Business\Validator\MerchantProductOfferItemValidatorInterface;
use Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToMerchantFacadeInterface;
use Spryker\Zed\MerchantProductOffer\MerchantProductOfferDependencyProvider;

class MerchantProductOfferBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOffer\Business\Validator\MerchantProductOfferItemValidatorInterface
     */
    public function createMerchantProductOfferItemValidator(): MerchantProductOfferItemValidatorInterface
    {
        return new MerchantProductOfferItemValidator(
            $this->getMerchantFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToMerchantFacadeInterface
     */
    protected function getMerchantFacade(): MerchantProductOfferToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferDependencyProvider::FACADE_MERCHANT);
    }
}
