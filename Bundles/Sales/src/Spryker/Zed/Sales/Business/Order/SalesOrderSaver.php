<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface;
use Spryker\Zed\Sales\Business\Model\Order\OrderReferenceGeneratorInterface;
use Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaver as SalesOrderSaverWithoutItemShipmentAddress;
use Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutorInterface;
use Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemMapperInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\SalesConfig;

class SalesOrderSaver extends SalesOrderSaverWithoutItemShipmentAddress
{
    /**
     * @deprecated Will be removed in next major release.
     *
     * @var \Spryker\Zed\Sales\Business\Order\SalesOrderSaverQuoteDataBCForMultiShipmentAdapterInterface
     */
    protected $quoteDataBCForMultiShipmentAdapter;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface $countryFacade
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderReferenceGeneratorInterface $orderReferenceGenerator
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfiguration
     * @param \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface $localeQueryContainer
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface[] $orderExpanderPreSavePlugins
     * @param \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutorInterface $salesOrderSaverPluginExecutor
     * @param \Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemMapperInterface $salesOrderItemMapper
     * @param \Spryker\Zed\Sales\Business\Order\SalesOrderSaverQuoteDataBCForMultiShipmentAdapterInterface $quoteDataBCForMultiShipmentAdapter
     */
    public function __construct(
        SalesToCountryInterface $countryFacade,
        SalesToOmsInterface $omsFacade,
        OrderReferenceGeneratorInterface $orderReferenceGenerator,
        SalesConfig $salesConfiguration,
        LocaleQueryContainerInterface $localeQueryContainer,
        Store $store,
        $orderExpanderPreSavePlugins,
        SalesOrderSaverPluginExecutorInterface $salesOrderSaverPluginExecutor,
        SalesOrderItemMapperInterface $salesOrderItemMapper,
        SalesOrderSaverQuoteDataBCForMultiShipmentAdapterInterface $quoteDataBCForMultiShipmentAdapter
    ) {
        parent::__construct(
            $countryFacade,
            $omsFacade,
            $orderReferenceGenerator,
            $salesConfiguration,
            $localeQueryContainer,
            $store,
            $orderExpanderPreSavePlugins,
            $salesOrderSaverPluginExecutor,
            $salesOrderItemMapper
        );

        $this->quoteDataBCForMultiShipmentAdapter = $quoteDataBCForMultiShipmentAdapter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderSales(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        /**
         * @deprecated Will be removed in next major release.
         */
        $quoteTransfer = $this->quoteDataBCForMultiShipmentAdapter->adapt($quoteTransfer);

        parent::saveOrderSales($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function hydrateAddresses(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        $billingAddressEntity = $this->saveSalesOrderAddress($quoteTransfer->getBillingAddress());
        $salesOrderEntity->setBillingAddress($billingAddressEntity);
    }
}
