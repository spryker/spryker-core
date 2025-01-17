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
     * @var string
     */
    protected const ORDER_AMENDMENT_QUOTE_PROCESS_FLOW_NAME = 'order-amendment';

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

    /**
     * Specification:
     * - Defines the name of quote process flow for order amendment.
     *
     * @api
     *
     * @return string
     */
    public function getOrderAmendmentQuoteProcessFlowName(): string
    {
        return static::ORDER_AMENDMENT_QUOTE_PROCESS_FLOW_NAME;
    }
}
