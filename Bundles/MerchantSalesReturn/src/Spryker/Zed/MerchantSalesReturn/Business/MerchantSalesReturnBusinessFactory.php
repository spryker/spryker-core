<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSalesReturn\Business\Model\MerchantReturnPreCreator;
use Spryker\Zed\MerchantSalesReturn\Business\Model\MerchantReturnPreCreatorInterface;
use Spryker\Zed\MerchantSalesReturn\Business\Model\MerchantReturnValidator;
use Spryker\Zed\MerchantSalesReturn\Business\Model\MerchantReturnValidatorInterface;
use Spryker\Zed\MerchantSalesReturn\Dependency\Facade\MerchantSalesReturnToSalesFacadeInterface;
use Spryker\Zed\MerchantSalesReturn\MerchantSalesReturnDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSalesReturn\MerchantSalesReturnConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesReturn\Persistence\MerchantSalesReturnQueryContainer getQueryContainer()
 */
class MerchantSalesReturnBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesReturn\Business\Model\MerchantReturnPreCreatorInterface
     */
    public function createMerchantReturnPreCreator(): MerchantReturnPreCreatorInterface
    {
        return new MerchantReturnPreCreator($this->getSalesFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturn\Business\Model\MerchantReturnValidatorInterface
     */
    public function createMerchantReturnValidator(): MerchantReturnValidatorInterface
    {
        return new MerchantReturnValidator();
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturn\Dependency\Facade\MerchantSalesReturnToSalesFacadeInterface
     */
    public function getSalesFacade(): MerchantSalesReturnToSalesFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnDependencyProvider::FACADE_SALES);
    }
}
