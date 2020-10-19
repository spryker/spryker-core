<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantCategory\Business\Reader\MerchantCategoryReader;
use Spryker\Zed\MerchantCategory\Business\Reader\MerchantCategoryReaderInterface;

/**
 * @method \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface getRepository()
 */
class MerchantCategoryBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantCategory\Business\Reader\MerchantCategoryReaderInterface
     */
    public function createMerchantCategoryReader(): MerchantCategoryReaderInterface
    {
        return new MerchantCategoryReader(
            $this->getRepository()
        );
    }
}
