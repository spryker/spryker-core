<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Updater;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Shared\Kernel\StrategyResolverInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Sales\Business\Mapper\SalesOrderMapperInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToLocaleInterface;
use Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface;
use Spryker\Zed\Sales\SalesConfig;

class SalesOrderUpdater implements SalesOrderUpdaterInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface $salesEntityManager
     * @param \Spryker\Zed\Sales\Business\Mapper\SalesOrderMapperInterface $salesOrderMapper
     * @param \Spryker\Zed\Sales\Business\Updater\SalesOrderAddressUpdaterInterface $salesOrderAddressUpdater
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfig
     * @param list<\Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface> $orderExpanderPreSavePlugins
     * @param \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface>> $orderPostSavePluginStrategyResolver
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToLocaleInterface $localeFacade
     */
    public function __construct(
        protected SalesEntityManagerInterface $salesEntityManager,
        protected SalesOrderMapperInterface $salesOrderMapper,
        protected SalesOrderAddressUpdaterInterface $salesOrderAddressUpdater,
        protected SalesConfig $salesConfig,
        protected array $orderExpanderPreSavePlugins,
        protected StrategyResolverInterface $orderPostSavePluginStrategyResolver,
        protected SalesToLocaleInterface $localeFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function updateOrderByQuote(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SaveOrderTransfer {
        $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer, $saveOrderTransfer): void {
            $this->executeUpdateOrderByQuoteTransaction($quoteTransfer, $saveOrderTransfer);
        });

        return $saveOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function executeUpdateOrderByQuoteTransaction(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SaveOrderTransfer {
        $orderTransfer = $quoteTransfer->getOriginalOrderOrFail();
        $orderTransfer = $this->salesOrderMapper->mapQuoteTransferToOrderTransfer($quoteTransfer, $orderTransfer);
        $orderTransfer = $this->salesOrderAddressUpdater->updateSalesOrderAddressesByQuote($quoteTransfer, $orderTransfer);

        $salesOrderEntityTransfer = $this->salesOrderMapper->mapOrderTransferToSalesOrderEntityTransfer(
            $orderTransfer,
            new SpySalesOrderEntityTransfer(),
        );
        $salesOrderEntityTransfer = $this->addLocale($salesOrderEntityTransfer);
        $salesOrderEntityTransfer = $this->executeOrderExpanderPreSavePlugins($quoteTransfer, $salesOrderEntityTransfer);
        $salesOrderEntityTransfer = $this->salesEntityManager->saveOrderEntity($salesOrderEntityTransfer);

        $saveOrderTransfer = $this->hydrateSaveOrderTransfer($saveOrderTransfer, $quoteTransfer, $salesOrderEntityTransfer);

        return $this->executeOrderPostSavePlugins($saveOrderTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function hydrateSaveOrderTransfer(
        SaveOrderTransfer $saveOrderTransfer,
        QuoteTransfer $quoteTransfer,
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer
    ): SaveOrderTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $saveOrderTransfer->addOrderItem(clone $itemTransfer);
        }

        return $saveOrderTransfer->fromArray($salesOrderEntityTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    protected function addLocale(SpySalesOrderEntityTransfer $salesOrderEntityTransfer): SpySalesOrderEntityTransfer
    {
        return $salesOrderEntityTransfer->setFkLocale($this->localeFacade->getCurrentLocale()->getIdLocale());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    protected function executeOrderExpanderPreSavePlugins(
        QuoteTransfer $quoteTransfer,
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer
    ): SpySalesOrderEntityTransfer {
        foreach ($this->orderExpanderPreSavePlugins as $preSaveHydrateOrderPlugin) {
            $salesOrderEntityTransfer = $preSaveHydrateOrderPlugin->expand($salesOrderEntityTransfer, $quoteTransfer);
        }

        return $salesOrderEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function executeOrderPostSavePlugins(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): SaveOrderTransfer
    {
        $quoteProcessFlowName = $quoteTransfer->getQuoteProcessFlow()?->getNameOrFail();
        $orderPostSavePlugins = $this->orderPostSavePluginStrategyResolver->get($quoteProcessFlowName);

        foreach ($orderPostSavePlugins as $orderPostSavePlugin) {
            $saveOrderTransfer = $orderPostSavePlugin->execute($saveOrderTransfer, $quoteTransfer);
        }

        return $saveOrderTransfer;
    }
}
