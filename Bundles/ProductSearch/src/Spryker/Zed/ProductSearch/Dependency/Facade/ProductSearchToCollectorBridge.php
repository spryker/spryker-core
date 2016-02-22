<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Dependency\Facade;

use Spryker\Zed\Collector\Business\CollectorFacade;

class ProductSearchToCollectorBridge implements ProductSearchToCollectorInterface
{

    /**
     * @var \Spryker\Zed\Collector\Business\CollectorFacade
     */
    protected $collectorFacade;

    /**
     * @param \Spryker\Zed\Collector\Business\CollectorFacade $collectorFacade
     */
    public function __construct($collectorFacade)
    {
        $this->collectorFacade = $collectorFacade;
    }

    /**
     * @return string
     */
    public function getSearchIndexName()
    {
        return $this->collectorFacade->getSearchIndexName();
    }

    /**
     * @return string
     */
    public function getSearchDocumentType()
    {
        return $this->collectorFacade->getSearchDocumentType();
    }

}
