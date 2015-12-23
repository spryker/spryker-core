<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Dependency\Facade;

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
