<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProduct\Business\Merchant\MerchantReader;
use Spryker\Zed\MerchantProduct\Business\Merchant\MerchantReaderInterface;

/**
 * @method \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProduct\MerchantProductConfig getConfig()
 */
class MerchantProductBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProduct\Business\Merchant\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader($this->getRepository());
    }
}
