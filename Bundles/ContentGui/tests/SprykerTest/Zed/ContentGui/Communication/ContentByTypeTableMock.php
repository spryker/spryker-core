<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentGui\Communication;

use Spryker\Zed\ContentGui\Communication\Table\ContentByTypeTable;
use Symfony\Component\HttpFoundation\Request;

class ContentByTypeTableMock extends ContentByTypeTable
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
