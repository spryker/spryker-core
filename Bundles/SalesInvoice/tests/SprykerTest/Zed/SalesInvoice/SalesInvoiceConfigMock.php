<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesInvoice;

use Spryker\Zed\SalesInvoice\SalesInvoiceConfig;

class SalesInvoiceConfigMock extends SalesInvoiceConfig
{
    /**
     * @return string
     */
    public function getOrderInvoiceTemplatePath(): string
    {
        return '';
    }
}
