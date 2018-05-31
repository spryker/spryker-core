<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductAlternativeGui\Communication\Form\AddAlternativeFormType;
use Spryker\Zed\ProductAlternativeGui\Communication\Form\DataProvider\AddAlternativeFormDataProvider;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductFacadeInterface;
use Spryker\Zed\ProductAlternativeGui\ProductAlternativeGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAlternativeGui\ProductAlternativeGuiConfig getConfig()
 */
class ProductAlternativeGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductAlternativeGui\Communication\Form\AddAlternativeFormType
     */
    public function createAddProductAlternativeFormType(): AddAlternativeFormType
    {
        return new AddAlternativeFormType();
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeGui\Communication\Form\DataProvider\AddAlternativeFormDataProvider
     */
    public function createAddProductAlternativeFormDataProvider(): AddAlternativeFormDataProvider
    {
        return new AddAlternativeFormDataProvider(
            $this->getProductFacade(),
            $this->getProductAlternativeFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductAlternativeGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductAlternativeGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeGui\Dependency\Facade\ProductAlternativeGuiToProductAlternativeFacadeInterface
     */
    public function getProductAlternativeFacade(): ProductAlternativeGuiToProductAlternativeFacadeInterface
    {
        return $this->getProvidedDependency(ProductAlternativeGuiDependencyProvider::FACADE_PRODUCT_ALTERNATIVE);
    }
}
