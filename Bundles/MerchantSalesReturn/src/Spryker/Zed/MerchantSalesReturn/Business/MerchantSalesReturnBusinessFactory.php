<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSalesReturn\Business\Creator\MerchantReturnCreator;
use Spryker\Zed\MerchantSalesReturn\Business\Creator\MerchantReturnCreatorInterface;
use Spryker\Zed\MerchantSalesReturn\Business\Validator\MerchantReturnValidator;
use Spryker\Zed\MerchantSalesReturn\Business\Validator\MerchantReturnValidatorInterface;
use Spryker\Zed\MerchantSalesReturn\Dependency\Facade\MerchantSalesReturnToMerchantSalesOrderFacadeInterface;
use Spryker\Zed\MerchantSalesReturn\MerchantSalesReturnDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSalesReturn\MerchantSalesReturnConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesReturn\Persistence\MerchantSalesReturnQueryContainer getQueryContainer()
 */
class MerchantSalesReturnBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesReturn\Business\Creator\MerchantReturnCreatorInterface
     */
    public function createMerchantReturnPreCreator(): MerchantReturnCreatorInterface
    {
        return new MerchantReturnCreator($this->getMerchantSalesOrderFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturn\Business\Validator\MerchantReturnValidatorInterface
     */
    public function createMerchantReturnValidator(): MerchantReturnValidatorInterface
    {
        return new MerchantReturnValidator();
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturn\Dependency\Facade\MerchantSalesReturnToMerchantSalesOrderFacadeInterface
     */
    public function getMerchantSalesOrderFacade(): MerchantSalesReturnToMerchantSalesOrderFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnDependencyProvider::FACADE_MERCHANT_SALES_ORDER);
    }
}
