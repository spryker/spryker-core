<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesOrderAmendment;

interface SalesOrderAmendmentClientInterface
{
    /**
     * Specification:
     * - Deletes the current quote and cancels the order amendment process for the amendable order.
     *
     * @api
     *
     * @return void
     */
    public function cancelOrderAmendment(): void;
}
