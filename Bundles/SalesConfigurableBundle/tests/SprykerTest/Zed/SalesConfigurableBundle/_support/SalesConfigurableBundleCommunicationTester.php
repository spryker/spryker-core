<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesConfigurableBundle;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle;
use Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem;
use Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItemQuery;
use Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleQuery;
use Propel\Runtime\Collection\ObjectCollection;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\SalesConfigurableBundle\Business\SalesConfigurableBundleFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesConfigurableBundleCommunicationTester extends Actor
{
    use _generated\SalesConfigurableBundleCommunicationTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @return void
     */
    public function ensureSalesOrderConfiguredBundleItemDatabaseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty(
            $this->getSalesOrderConfiguredBundleItemQuery(),
        );
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem
     */
    public function findSalesOrderConfiguredBundleItem(int $idSalesOrderItem): SpySalesOrderConfiguredBundleItem
    {
        return $this->getSalesOrderConfiguredBundleItemQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->findOne();
    }

    /**
     * @return \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItemQuery
     */
    public function getSalesOrderConfiguredBundleItemQuery(): SpySalesOrderConfiguredBundleItemQuery
    {
        return SpySalesOrderConfiguredBundleItemQuery::create();
    }

    /**
     * @return \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleQuery
     */
    public function getSalesOrderConfiguredBundleQuery(): SpySalesOrderConfiguredBundleQuery
    {
        return SpySalesOrderConfiguredBundleQuery::create();
    }

    /**
     * @return \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle
     */
    public function createSalesOrderConfiguredBundle(): SpySalesOrderConfiguredBundle
    {
        $salesOrderConfiguredBundleEntity = (new SpySalesOrderConfiguredBundle())
            ->setConfigurableBundleTemplateUuid('test-uuid')
            ->setName('test name');
        $salesOrderConfiguredBundleEntity->save();

        return $salesOrderConfiguredBundleEntity;
    }

    /**
     * @param int $idSalesOrderConfiguredBundle
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem
     */
    public function createSalesOrderConfiguredBundleItem(
        int $idSalesOrderConfiguredBundle,
        int $idSalesOrderItem
    ): SpySalesOrderConfiguredBundleItem {
        $salesOrderConfiguredBundleItemEntity = (new SpySalesOrderConfiguredBundleItem())
            ->setFkSalesOrderConfiguredBundle($idSalesOrderConfiguredBundle)
            ->setFkSalesOrderItem($idSalesOrderItem)
            ->setConfigurableBundleTemplateSlotUuid('test-uuid');
        $salesOrderConfiguredBundleItemEntity->save();

        return $salesOrderConfiguredBundleItemEntity;
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem>
     */
    public function getSalesOrderConfiguredBundleItemEntities(): ObjectCollection
    {
        return $this->getSalesOrderConfiguredBundleItemQuery()->find();
    }

    /**
     * @param bool|null $withConfiguredBundle
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createOrder(?bool $withConfiguredBundle = false): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->withAnotherItem()
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        if ($withConfiguredBundle) {
            $configuredBundleItemTransfer = (new ConfiguredBundleItemTransfer())
                ->setSlot(
                    (new ConfigurableBundleTemplateSlotTransfer())->setUuid('slot-uuid'),
                );

            $configuredBundleTransfer = (new ConfiguredBundleTransfer())
                ->setGroupKey('group-key')
                ->setQuantity(1)
                ->setTemplate(
                    (new ConfigurableBundleTemplateTransfer())->setUuid('template-uuid')->setName('template-name'),
                );

            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
            $itemTransfer
                ->setConfiguredBundle($configuredBundleTransfer)
                ->setConfiguredBundleItem($configuredBundleItemTransfer);
        }

        $storeTransfer = $this->haveStore([StoreTransfer::NAME => 'DE']);
        $quoteTransfer->setStore($storeTransfer);

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);
        $quoteTransfer->setItems($saveOrderTransfer->getOrderItems());

        return $quoteTransfer;
    }
}
