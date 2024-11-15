<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesOrderAmendmentConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines the format of the quote name for the cart reorder.
     *
     * @api
     *
     * @return string
     */
    public function getCartReorderQuoteNameFormat(): string
    {
        return 'Editing Order %s';
    }
}
