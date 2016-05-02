<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSearch\Business\Map\SearchProductAttributeMapCollector;
use Spryker\Zed\ProductSearch\Business\Marker\ProductSearchMarker;
use Spryker\Zed\ProductSearch\ProductSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSearch\ProductSearchConfig getConfig()
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainer getQueryContainer()
 */
class ProductSearchBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Marker\ProductSearchMarkerInterface
     */
    public function createProductSearchMarker()
    {
        return new ProductSearchMarker(
            $this->getTouchFacade(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Map\SearchProductAttributeMapCollectorInterface
     */
    public function createSearchProductAttributeMapCollector()
    {
        return new SearchProductAttributeMapCollector($this->getQueryContainer());
    }

}
