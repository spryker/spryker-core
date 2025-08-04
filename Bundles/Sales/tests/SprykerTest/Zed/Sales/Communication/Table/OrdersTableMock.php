<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Communication\Table;

use Spryker\Zed\Sales\Communication\Table\OrdersTable;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class OrdersTableMock extends OrdersTable
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

    /**
     * @param \Twig\Environment $twig
     *
     * @return void
     */
    public function setTwig(Environment $twig): void
    {
        $this->twig = $twig;
    }
}
