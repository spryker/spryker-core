<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Communication\Table;

use Spryker\Zed\ProductOption\Communication\Table\ProductOptionListTable;
use Symfony\Component\HttpFoundation\Request;

class ProductOptionListTableMock extends ProductOptionListTable
{
    /**
     * @return array
     */
    public function fetchData(): array
    {
        return $this->init()->prepareData($this->config);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return new Request();
    }
}
