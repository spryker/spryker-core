<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ConfigurableBundleGui\Communication\Table;

use Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateTable;
use Symfony\Component\HttpFoundation\Request;

class ConfigurableBundleTemplateTableMock extends ConfigurableBundleTemplateTable
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
