<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferGui\Communication\Table;

use Spryker\Zed\ProductOfferGui\Communication\Table\ProductOfferTable;
use Symfony\Component\HttpFoundation\Request;

class ProductOfferTableMock extends ProductOfferTable
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
