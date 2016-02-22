<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container;

use Spryker\Shared\Payone\PayoneApiConstants;

class GetInvoiceContainer extends AbstractRequestContainer
{

    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_GETINVOICE;

    /**
     * @var string
     */
    protected $invoice_title;

    /**
     * @param string $invoice_title
     *
     * @return void
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
