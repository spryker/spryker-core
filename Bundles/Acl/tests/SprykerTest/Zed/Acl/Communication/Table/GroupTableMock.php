<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Acl\Communication\Table;

use Spryker\Zed\Acl\Communication\Table\GroupTable;
use Symfony\Component\HttpFoundation\Request;

class GroupTableMock extends GroupTable
{
    /**
     * @return $this
     */
    protected function init()
    {
        $this->request = new Request();
        $config = $this->newTableConfiguration();
        $config->setPageLength($this->getLimit());
        $config = $this->configure($config);
        $this->setConfiguration($config);

        return $this;
    }

    /**
     * @return array
     */
    public function fetchData(): array
    {
        $this->init();

        return $this->prepareData($this->config);
    }
}
