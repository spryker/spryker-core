<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication;

use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Table\MerchantReturnTable;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Service\MerchantSalesReturnMerchantUserGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\MerchantSalesReturnMerchantUserGuiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSalesReturnMerchantUserGui\MerchantSalesReturnMerchantUserGuiConfig getConfig()
 */
class MerchantSalesReturnMerchantUserGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Table\MerchantReturnTable
     */
    public function createMerchantReturnTable(): MerchantReturnTable
    {
        return new MerchantReturnTable(
            $this->getDateTimeService(),
            $this->getConfig(),
            $this->getSalesReturnPropelQuery(),
            $this->getMerchantUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Service\MerchantSalesReturnMerchantUserGuiToUtilDateTimeServiceInterface
     */
    public function getDateTimeService(): MerchantSalesReturnMerchantUserGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::SERVICE_DATE_TIME);
    }

    /**
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery
     */
    public function getSalesReturnPropelQuery(): SpySalesReturnQuery
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::PROPEL_QUERY_SALES_RETURN);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantSalesReturnMerchantUserGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface
     */
    public function getSalesReturnFacade(): MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::FACADE_SALES_RETURN);
    }
}
