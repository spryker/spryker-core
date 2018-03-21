<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\OfferGui\Communication\Table\OffersTable;
use Spryker\Zed\OfferGui\Communication\Table\OffersTableQueryBuilder;
use Spryker\Zed\OfferGui\Communication\Table\OffersTableQueryBuilderInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCustomerFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMoneyFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilSanitizeServiceInterface;
use Spryker\Zed\OfferGui\OfferGuiDependencyProvider;

class OfferGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\OfferGui\Communication\Table\OffersTable
     */
    public function createOffersTable(): OffersTable
    {
        return new OffersTable(
            $this->createOffersTableQueryBuilder(),
            $this->getMoneyFacade(),
            $this->getCustomerFacade(),
            $this->getUtilSanitize(),
            $this->getUtilDateTimeService()
        );
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilSanitizeServiceInterface
     */
    public function getUtilSanitize(): OfferGuiToUtilSanitizeServiceInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Service\OfferGuiToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): OfferGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): OfferGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): OfferGuiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\OfferGui\Communication\Table\OffersTableQueryBuilderInterface
     */
    public function createOffersTableQueryBuilder(): OffersTableQueryBuilderInterface
    {
        return new OffersTableQueryBuilder(
            $this->getPropelQuerySalesOrder()
        );
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function getPropelQuerySalesOrder()
    {
        return $this->getProvidedDependency(OfferGuiDependencyProvider::PROPEL_QUERY_SALES_ORDER);
    }
}
