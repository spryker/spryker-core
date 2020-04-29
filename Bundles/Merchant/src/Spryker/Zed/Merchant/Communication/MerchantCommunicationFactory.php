<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Merchant\Communication\Plugin\Cart\Validator\MerchantCartValidator;
use Spryker\Zed\Merchant\Communication\Plugin\Cart\Validator\MerchantCartValidatorInterface;
use Spryker\Zed\Merchant\Communication\Plugin\Checkout\Validator\MerchantCheckoutValidator;
use Spryker\Zed\Merchant\Communication\Plugin\Checkout\Validator\MerchantCheckoutValidatorInterface;

/**
 * @method \Spryker\Zed\Merchant\Business\MerchantFacade getFacade()
 * @method \Spryker\Zed\MerchantGui\MerchantGuiConfig getConfig()
 * @method \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface getRepository()
 */
class MerchantCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Merchant\Communication\Plugin\Cart\Validator\MerchantCartValidatorInterface
     */
    public function createMerchantCartValidator(): MerchantCartValidatorInterface
    {
        return new MerchantCartValidator($this->getFacade());
    }

    /**
     * @return \Spryker\Zed\Merchant\Communication\Plugin\Checkout\Validator\MerchantCheckoutValidatorInterface
     */
    public function createMerchantCheckoutValidator(): MerchantCheckoutValidatorInterface
    {
        return new MerchantCheckoutValidator($this->getFacade());
    }
}
