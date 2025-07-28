<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Communication\Table;

use Spryker\Zed\Discount\Communication\Table\DiscountsTable;
use Symfony\Component\HttpFoundation\Request;

class DiscountsTableMock extends DiscountsTable
{
    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(): Request
    {
        $request = new Request();

        return $request;
    }
}
