<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Communication\Table;

use Spryker\Zed\ProductManagement\Communication\Table\ProductTable;
use Symfony\Component\HttpFoundation\Request;

class ProductTableMock extends ProductTable
{
    /**
     * @var string|null
     */
    protected $searchTerm;

    /**
     * @param string $searchTerm
     *
     * @return void
     */
    public function setSearchTerm(string $searchTerm): void
    {
        $this->searchTerm = $searchTerm;
    }

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
    protected function getRequest(): Request
    {
        $request = new Request();

        if ($this->searchTerm !== null) {
            $request->query->set('search', ['value' => $this->searchTerm]);
        }

        return $request;
    }
}
