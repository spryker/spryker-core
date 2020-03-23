<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabelSearch\Business\PageData\ProductPageDataTransferExpander;
use Spryker\Zed\ProductLabelSearch\ProductLabelSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelSearch\ProductLabelSearchConfig getConfig()
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchRepositoryInterface getRepository()
 */
class ProductLabelSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductLabelSearch\Business\PageData\ProductPageDataTransferExpanderInterface
     */
    public function createProductPageDataTransferExpander()
    {
        return new ProductPageDataTransferExpander($this->getProductLabelFacade());
    }

    /**
     * @return \Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductLabelInterface
     */
    public function getProductLabelFacade()
    {
        return $this->getProvidedDependency(ProductLabelSearchDependencyProvider::FACADE_PRODUCT_LABEL);
    }
}
