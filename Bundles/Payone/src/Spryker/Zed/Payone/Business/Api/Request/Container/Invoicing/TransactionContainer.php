<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing;

use Spryker\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class TransactionContainer extends AbstractContainer
{

    /**
     * @var string
     */
    protected $invoiceid;

    /**
     * @var string
     */
    protected $invoice_deliverymode;

    /**
     * @var string
     */
    protected $invoice_deliverydate;

    /**
     * @var string
     */
    protected $invoice_deliveryenddate;

    /**
     * @var string
     */
    protected $invoiceappendix;

    /**
     * @var \Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer[]
     */
    protected $items = [];

    /**
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        $i = 1;
        foreach ($this->items as $item) {
            /** @var \Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer $item */
            $data = array_merge($data, $item->toArrayByKey($i));
            $i++;
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasItems()
    {
        return (count($this->items) > 0);
    }

    /**
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer $item
     *
     * @return void
     */
    public function addItem(ItemContainer $item)
    {
        $this->items[] = $item;
    }

    /**
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer[] $items
     *
     * @return void
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param string $invoice_deliverydate
     *
     * @return void
     */
    public function setInvoiceDeliverydate($invoice_deliverydate)
    {
        $this->invoice_deliverydate = $invoice_deliverydate;
    }

    /**
     * @return string
     */
    public function getInvoiceDeliverydate()
    {
        return $this->invoice_deliverydate;
    }

    /**
     * @param string $invoice_deliveryenddate
     *
     * @return void
     */
    public function setInvoiceDeliveryenddate($invoice_deliveryenddate)
    {
        $this->invoice_deliveryenddate = $invoice_deliveryenddate;
    }

    /**
     * @return string
     */
    public function getInvoiceDeliveryenddate()
    {
        return $this->invoice_deliveryenddate;
    }

    /**
     * @param string $invoice_deliverymode
     *
     * @return void
     */
    public function setInvoiceDeliverymode($invoice_deliverymode)
    {
        $this->invoice_deliverymode = $invoice_deliverymode;
    }

    /**
     * @return string
     */
    public function getInvoiceDeliverymode()
    {
        return $this->invoice_deliverymode;
    }

    /**
     * @param string $invoiceappendix
     *
     * @return void
     */
    public function setInvoiceappendix($invoiceappendix)
    {
        $this->invoiceappendix = $invoiceappendix;
    }

    /**
     * @return string
     */
    public function getInvoiceappendix()
    {
        return $this->invoiceappendix;
    }

    /**
     * @param string $invoiceid
     *
     * @return void
     */
    public function setInvoiceid($invoiceid)
    {
        $this->invoiceid = $invoiceid;
    }

    /**
     * @return string
     */
    public function getInvoiceid()
    {
        return $this->invoiceid;
    }

}
