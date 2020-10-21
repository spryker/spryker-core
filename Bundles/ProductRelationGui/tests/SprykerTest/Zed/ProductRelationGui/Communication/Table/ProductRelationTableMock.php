<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelationGui\Communication\Table;

use Spryker\Zed\ProductRelationGui\Communication\Table\ProductRelationTable;
use Symfony\Component\HttpFoundation\Request;

class ProductRelationTableMock extends ProductRelationTable
{
    /**
     * @return array
     */
    public function fetchData(): array
    {
        $this->init();

        return $this->prepareData($this->config);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return new Request();
    }
}
