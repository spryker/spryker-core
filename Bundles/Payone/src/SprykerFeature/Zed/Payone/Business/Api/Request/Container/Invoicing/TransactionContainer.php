<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Invoicing;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

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
     * @var ItemContainer[]
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
            /* @var ItemContainer $item */
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
     * @param ItemContainer $item
     */
    public function addItem(ItemContainer $item)
    {
        $this->items[] = $item;
    }

    /**
     * @param ItemContainer[] $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return ItemContainer[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param string $invoice_deliverydate
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
