<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Presentation\PageObject;

use SprykerTest\Zed\Sales\PresentationTester;

class SalesDetailPage
{

    const URL = '/sales/detail?id-sales-order=';

    const SELECTOR_ID_SALES_ORDER_ITEM = '//div[@id="items"]//table/tbody/tr/td[{{position}}]/input';
    const SELECTOR_SALES_ORDER_ROW = '//div[@id="items"]//table/tbody/tr/td[{{position}}]/input';
    const SELECTOR_ITEM_TOTAL_ELEMENT = '//table[@data-qa="order-item-list"]/tbody/tr[@data-qa-item-row="{{idSalesOrderItem}}"]/td[@data-qa="item-total-amount"]';
    const SELECTOR_CURRENT_STATE = '//td[@data-qa-item-current-state={{idSalesOrderItem}}]';

    const SELECTOR_GRAND_TOTAL = '//td[@data-qa="grand-total"]';

    const ATTRIBUTE_ITEM_TOTAL_RAW = 'data-qa-raw';
    const ATTRIBUTE_GRAND_TOTAL_RAW = 'data-qa-grand-total-raw';

    /**
     * @var \SprykerTest\Zed\Sales\PresentationTester
     */
    protected $tester;

    /**
     * @param \SprykerTest\Zed\Sales\PresentationTester $i
     */
    public function __construct(PresentationTester $i)
    {
        $this->tester = $i;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string
     */
    public static function getOrderDetailsPageUrl($idSalesOrder)
    {
        return static::URL . $idSalesOrder;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return string
     */
    public static function getCurrentStateSelector($idSalesOrderItem)
    {
        return str_replace('{{idSalesOrderItem}}', $idSalesOrderItem, static::SELECTOR_CURRENT_STATE);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return string
     */
    public static function getItemTotalElementSelector($idSalesOrderItem)
    {
        return str_replace('{{idSalesOrderItem}}', $idSalesOrderItem, static::SELECTOR_ITEM_TOTAL_ELEMENT);
    }

    /**
     * @param int $rowPosition Position of row in list, starts with 1
     *
     * @return string
     */
    public static function getIdSalesOrderItemSelector($rowPosition)
    {
        return str_replace('{{position}}', $rowPosition, static::SELECTOR_ID_SALES_ORDER_ITEM);
    }

    /**
     * @param int $rowPosition Position of row in list, starts with 1
     *
     * @return string
     */
    public static function getSalesOrderItemRowSelector($rowPosition)
    {
        return str_replace('{{position}}', $rowPosition, static::SELECTOR_SALES_ORDER_ROW);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function openDetailPageForOrder($idSalesOrder)
    {
        $i = $this->tester;
        $i->amOnPage(SalesDetailPage::getOrderDetailsPageUrl($idSalesOrder));
    }

    /**
     * This method assumes that we are already on a details page
     *
     * @param int $rowPosition
     *
     * @return int
     */
    public function grabIdSalesOrderItemFromRow($rowPosition)
    {
        $i = $this->tester;
        $idSalesOrderItem = $i->grabValueFrom(SalesDetailPage::getIdSalesOrderItemSelector($rowPosition));

        return $idSalesOrderItem;
    }

}
