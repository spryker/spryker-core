<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container;

class GetInvoiceContainer extends AbstractRequestContainer
{

    /**
     * @var string
     */
    protected $request = self::REQUEST_TYPE_GETINVOICE;

    /**
     * @var string
     */
    protected $invoice_title;

    /**
     * @param string $invoice_title
     */
    public function setInvoiceTitle($invoice_title)
    {
        $this->invoice_title = $invoice_title;
    }

    /**
     * @return string
     */
    public function getInvoiceTitle()
    {
        return $this->invoice_title;
    }

}
