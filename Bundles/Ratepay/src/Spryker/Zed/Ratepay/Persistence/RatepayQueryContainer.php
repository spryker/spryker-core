<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Persistence;

use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayItem;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Ratepay\Persistence\RatepayPersistenceFactory getFactory()
 */
class RatepayQueryContainer extends AbstractQueryContainer implements RatepayQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function queryPayments()
    {
        return $this->getFactory()->createPaymentRatepayQuery();
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function queryPaymentById($idPayment)
    {
        return $this
            ->queryPayments()
            ->filterByIdPaymentRatepay($idPayment);
    }

    /**
     * @api
     *
     * @param int $idPayment
     * @param string $paymentType
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function queryPaymentByIdAndPaymentType($idPayment, $paymentType)
    {
        return $this
            ->queryPayments()
            ->filterByIdPaymentRatepay($idPayment)
            ->filterByPaymentType($paymentType);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayQuery
     */
    public function queryPaymentBySalesOrderId($idSalesOrder)
    {
        return $this
            ->queryPayments()
            ->filterByFkSalesOrder($idSalesOrder);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLogQuery
     */
    public function queryPaymentLog()
    {
        return $this->getFactory()->createPaymentRatepayLogQuery();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItem
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayItem
     */
    public function addPaymentItem(ItemTransfer $orderItem)
    {
        $paymentRatepayItem = new SpyPaymentRatepayItem();
        $paymentRatepayItem->setFkSalesOrder($orderItem->getFkSalesOrder());
        $paymentRatepayItem->setSku($orderItem->getGroupKey());
        $paymentRatepayItem->setUnitGrossPriceWithProductOptions($orderItem->getUnitGrossPriceWithProductOptions());
        $paymentRatepayItem->setUnitTotalDiscountAmountWithProductOption($orderItem->getUnitTotalDiscountAmountWithProductOption());
        $paymentRatepayItem->setSumGrossPriceWithProductOptionAndDiscountAmounts($orderItem->getSumGrossPriceWithProductOptionAndDiscountAmounts());

        $paymentRatepayItem->save();

        return $paymentRatepayItem;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayItemQuery
     */
    public function queryPaymentItem()
    {
        return $this->getFactory()->createPaymentRatepayItemQuery();
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     * @param string $sku
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayItemQuery
     */
    public function queryPaymentItemByOrderIdAndSku($idSalesOrder, $sku)
    {
        return $this
            ->queryPaymentItem()
            ->filterByFkSalesOrder($idSalesOrder)
            ->filterBySku($sku);
    }

    /**
     * @api
     *
     * @param int $idPayment
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepayLogQuery
     */
    public function queryPaymentLogQueryBySalesOrderId($idPayment)
    {
        return $this
            ->queryPaymentLog()
            ->filterByFkSalesOrder($idPayment);
    }

}
